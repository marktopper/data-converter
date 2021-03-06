<?php

namespace App\Converters;

use App\Converters\Models\Language;

class LanguageConverter extends Converter
{
    public $xmlRoot = 'languages';

    public $convertFrom = 'array.php';

    public $model = Language::class;

    public $files = [
        [
            'filename' => 'array.json',
            'prettify' => true,
        ],
        [
            'filename' => 'indexedArray.json',
            'prettify' => true,
            'index' => 'code',
        ],
        [
            'filename' => 'array.php',
            'prettify' => true,
        ],
        [
            'filename' => 'indexedArray.php',
            'prettify' => true,
            'index' => 'code',
        ],
        [
            'filename' => 'array.xml',
            'prettify' => true,
        ],
        [
            'filename' => 'indexedArray.xml',
            'prettify' => true,
            'index' => 'code',
        ]
    ];

    public function getPath()
    {
        return base_path('repos/languages/src');
    }
}