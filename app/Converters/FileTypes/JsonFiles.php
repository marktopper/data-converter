<?php

namespace App\Converters\FileTypes;

use Illuminate\Support\Collection;

trait JsonFiles
{
    /**
     * Get content from path and turn into a Collection.
     *
     * @param string $path
     * @return Collection
     */
    public function fromJson($path)
    {
        return collect(json_decode(file_get_contents($path), true));
    }

    /**
     * Turn a Collection into Json string
     *
     * @param Collection $collection
     * @return string
     */
    public function toJson(Collection $collection)
    {
        return json_encode($collection->toArray());
    }

    /**
     * Prettify Json Content
     *
     * @param string $content
     * @return string
     */
    public function prettifyJson($content)
    {
        return prettify_json($content);
    }
}