<?php

namespace App\Converters\FileTypes;

use Illuminate\Support\Collection;

interface JsonFileInterface
{
    /**
     * Get content from path and turn into a Collection.
     *
     * @param string $path
     * @return Collection
     */
    public function fromJson($path);

    /**
     * Turn a Collection into Json string
     *
     * @param Collection $collection
     * @return string
     */
    public function toJson(Collection $collection);

    /**
     * Prettify Json Content
     *
     * @param string $content
     * @return string
     */
    public function prettifyJson($content);
}