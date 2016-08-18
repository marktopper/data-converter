<?php

namespace App\Converters;

use App\Converters\FileTypes;

abstract class Converter implements ConverterInterface,
    FileTypes\PhpFileInterface,
    FileTypes\JsonFileInterface,
    FileTypes\XmlFileInterface
{
    use FileTypes\PhpFiles,
        FileTypes\JsonFiles,
        FileTypes\XmlFiles;

    /**
     * The file to convert from.
     *
     * @var string
     */
    protected $convertFrom = 'array.php';

    /**
     * An array of files to convert.
     *
     * @var array
     */
    protected $files = [];

    /**
     * Class name of model.
     *
     * @var string
     */
    protected $model;

    /**
     * Handle the conversion.
     */
    public function handle()
    {
        $filename = $this->convertFrom;

        $file = collect($this->files)->first(function ($index, $item) use ($filename) {
            return array_get($item, 'filename') == $filename;
        });

        $method = array_get($file, 'from_method');

        if (is_null($method)) {
            $type = collect(explode('.', array_get($file, 'filename')))->last();
            $method = 'from' . studly_case($type);
        }

        $data = $this->$method($this->getPath() . DIRECTORY_SEPARATOR . $this->convertFrom);

        $model = $this->model;

        $data = $data->transform(function(array $data) use ($model) {
            return new $model($data);
        });

        foreach ($this->files as $file) {
            $method = array_get($file, 'to_method');

            if (is_null($method)) {
                $type = collect(explode('.', array_get($file, 'filename')))->last();
                $method = 'to' . studly_case($type);
            }

            $except = array_get($file, 'except', []);

            if (is_string($except)) {
                $except = [$except];
            }

            $indexing = array_get($file, 'index', null);

            $convertedData = $this->$method(
                !is_null($indexing) ? $data->except($except)->keyBy($indexing) : $data->except($except)
            );

            if (array_get($file, 'prettify', false)) {
                $method = array_get($file, 'prettify_method');

                if (is_null($method)) {
                    $type = collect(explode('.', array_get($file, 'filename')))->last();
                    $method = 'prettify' . studly_case($type);
                }

                $convertedData = $this->$method($convertedData);
            }

            file_put_contents($this->getPath() . DIRECTORY_SEPARATOR . array_get($file, 'filename'), $convertedData);
        }
    }
}