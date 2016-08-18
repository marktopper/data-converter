<?php

namespace App\Converters\FileTypes;

use Illuminate\Support\Collection;

trait XmlFiles
{
    /**
     * XML root element name.
     *
     * @var string
     */
    protected $xmlRoot = 'data';

    /**
     * Get content from path and turn into a Collection.
     *
     * @param string $path
     * @return Collection
     */
    // public function fromXml($content) {}

    /**
     * Turn a Collection into XML string.
     *
     * @param Collection $collection
     * @return string
     */
    public function toXml(Collection $collection)
    {
        $xml = new \SimpleXMLElement('<?xml version="1.0"?><'.$this->xmlRoot.'></'.$this->xmlRoot.'>');

        $this->toXmlLoop($collection->toArray(), $xml);

        return $xml->asXML();
    }

    /**
     * Prettify XML Content
     *
     * @param string $content
     * @return string
     */
    public function prettifyXml($content)
    {
        $dom = new \DOMDocument();
        $dom->preserveWhiteSpace = FALSE;
        $dom->loadXML($content);
        $dom->formatOutput = TRUE;

        return $dom->saveXML();
    }

    /**
     * Loop through array and turn into XML element.
     *
     * @param $data
     * @param \SimpleXMLElement $xml
     * @param null $parentKeyName
     */
    protected function toXmlLoop($data, \SimpleXMLElement &$xml, $parentKeyName = null) {
        foreach( $data as $key => $value ) {
            if( is_array($value) ) {
                if( is_numeric($key) OR empty($key)){
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