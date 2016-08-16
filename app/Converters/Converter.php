<?php

namespace App\Converters;

use Illuminate\Support\Collection;

abstract class Converter implements ConverterInterface
{
    public $xmlRoot = 'data';

    public $convertFrom = 'array.php';

    protected $files = [];

    public function handle()
    {
        $data = $this->getFromData();

        foreach ($this->files as $file) {
            $method = $this->getFileConvertToMethod($file);

            $indexing = $this->getFileIndexing($file);

            $convertedData = $this->$method(
                !is_null($indexing) ? $this->indexBy($data, $indexing) : $data
            );

            file_put_contents($this->getFilePath($file), $convertedData);

            if (array_get($file, 'prettify', false)) {
                $this->prettify($file);
            }
        }
    }

    protected function getFileIndexing($file)
    {
        return array_get($file, 'index', null);
    }

    protected function indexBy(Collection $collection, $index)
    {
        return $collection->keyBy($index);
    }

    protected function prettify(array $file)
    {
        $method = $this->getFilePrettifyMethod($file);

        $this->$method($file);
    }

    protected function prettifyPhp(array $file)
    {
        $path = $this->getFilePath($file);

        // Style-fix PHP file
        exec('php-cs-fixer fix '.$path);
    }

    protected function prettifyJson(array $file)
    {
        $path = $this->getFilePath($file);

        $content = $this->getFileContent($path);

        $content = prettify_json($content);

        file_put_contents($path, $content);
    }

    protected function prettifyXml(array $file)
    {
        $path = $this->getFilePath($file);

        $content = $this->getFileContent($path);

        $dom = new \DOMDocument();
        $dom->preserveWhiteSpace = FALSE;
        $dom->loadXML($content);
        $dom->formatOutput = TRUE;

        $content = $dom->saveXml();

        file_put_contents($path, $content);
    }

    protected function getFilePrettifyMethod(array $file)
    {
        $method = array_get($file, 'prettify_method');

        if (is_null($method)) {
            $type = collect(explode('.', array_get($file, 'filename')))->last();
            $method = 'prettify' . studly_case($type);
        }

        return $method;
    }

    protected function getFileConvertToMethod(array $file)
    {
        $method = array_get($file, 'to_method');

        if (is_null($method)) {
            $type = collect(explode('.', array_get($file, 'filename')))->last();
            $method = 'to' . studly_case($type);
        }

        return $method;
    }

    public function getFilePath(array $file)
    {
        $path = array_get($file, 'filename');

        return $this->getPath() . DIRECTORY_SEPARATOR . $path;
    }

    public function getFileType(array $file)
    {
        $filename = array_get($file, 'filename');

        $parts = explode('.', $filename);

        return end($parts);
    }

    public function getPathForType($type)
    {
        return $this->getPath() . DIRECTORY_SEPARATOR . $this->filenames[$type];
    }

    public function getFromData()
    {
        return collect($this->formatFromData());
    }

    public function getFromFile()
    {
        $filename = $this->convertFrom;

        return collect($this->files)->first(function ($index, $item) use ($filename) {
            return array_get($item, 'filename') == $filename;
        });
    }

    public function getFileConvertFromMethod(array $file)
    {
        $method = array_get($file, 'from_method');

        if (is_null($method)) {
            $type = collect(explode('.', array_get($file, 'filename')))->last();
            $method = 'from' . studly_case($type);
        }

        return $method;
    }

    public function formatFromData()
    {
        $method = $this->getFileConvertFromMethod(
            $this->getFromFile()
        );

        return $this->$method($this->getFromDataPath());
    }

    public function getFromDataPath()
    {
        return $this->getPath() . DIRECTORY_SEPARATOR . $this->convertFrom;
    }

    public function getFileContent($path)
    {
        return file_get_contents($path);
    }

    public function fromJson($path)
    {
        return json_decode($this->getFileContent($path), true);
    }

    public function fromPhp($path)
    {
        return include($path);
    }

    public function toPhp(Collection $collection)
    {
        return "<?php return " . var_export($collection->toArray(), true) . ";";
    }

    public function toXml(Collection $collection)
    {
        $xml = new \SimpleXMLElement('<?xml version="1.0"?><'.$this->xmlRoot.'></'.$this->xmlRoot.'>');

        $this->toXmlLoop($collection->toArray(), $xml);

        return $xml->asXML();
    }

    public function toJson(Collection $collection)
    {
        return json_encode($collection->toArray());
    }

    public function toXmlLoop($data, &$xml, $parentKeyName = null) {
        foreach( $data as $key => $value ) {
            if( is_array($value) ) {
                if( is_numeric($key) ){
                    if (!is_null($parentKeyName)) {
                        $key = str_singular($parentKeyName);
                    } else {
                        $key = str_singular($this->xmlRoot);
                    }
                }
                $subnode = $xml->addChild($key);
                $this->toXmlLoop($value, $subnode, $key);
            } else {
                $xml->addChild("$key",htmlspecialchars("$value"));
            }
        }
    }
}