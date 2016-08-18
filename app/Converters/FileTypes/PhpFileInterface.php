<?php

namespace App\Converters\FileTypes;

use Illuminate\Support\Collection;

interface PhpFileInterface
{
    /**
     * Get content from path and turn int into a Collection.
     *
     * @param string $path
     * @return Collection
     */
    public function fromPhp($path);

    /**
     * Turn a Collection into PHP file returning an array
     *
     * @param Collection $collection
     * @return string
     */
    public function toPhp(Collection $collection);

    /**
     * Prettify PHP Content
     *
     * @param string $content
     * @return string
     */
    public function prettifyPhp($content);
}