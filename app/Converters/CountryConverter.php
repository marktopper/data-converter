<?php

namespace App\Converters;

use App\Converters\Models\Country;

class CountryConverter extends Converter
{
    public $xmlRoot = 'countries';

    public $convertFrom = 'array.json';

    public $model = Country::class;

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
        return base_path('repos/countries/src');
    }
}