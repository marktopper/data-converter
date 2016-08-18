<?php

namespace App\Converters\FileTypes;

use Illuminate\Support\Collection;

trait PhpFiles
{
    /**
     * Get content from path and turn into a Collection.
     *
     * @param string $path
     * @return Collection
     */
    public function fromPhp($path)
    {
        $array = include($path);

        return collect($array);
    }

    /**
     * Turn a Collection into PHP file returning an array
     *
     * @param Collection $collection
     * @return string
     */
    public function toPhp(Collection $collection)
    {
        return "<?php return " . var_export($collection->toArray(), true) . ";";
    }

    /**
     * Prettify PHP Content
     *
     * @param string $content
     * @return string
     */
    public function prettifyPhp($content)
    {
        $path = tempnam(sys_get_temp_dir(), 'converter_').'.php';

        file_put_contents($path, $content);

        // Style-fix PHP file
        exec('php-cs-fixer fix '.$path);

        $content = file_get_contents($path);

        try {
            unlink($path);
        } catch (\Exception $e) {
            // do nothing
        }

        return $content;
    }
}