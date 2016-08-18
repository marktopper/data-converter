<?php

namespace App\Converters\FileTypes;

use Illuminate\Support\Collection;

interface XmlFileInterface
{
    /**
     * Get content from path and turn int into a Collection.
     *
     * @param string $path
     * @return Collection
     */
    //public function fromXml($path);

    /**
     * Turn a Collection into XML string
     *
     * @param Collection $collection
     * @return string
     */
    public function toXml(Collection $collection);

    /**
     * Prettify XML Content
     *
     * @param string $content
     * @return string
     */
    public function prettifyXml($content);
}