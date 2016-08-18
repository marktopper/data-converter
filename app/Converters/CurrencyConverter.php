<?php

namespace App\Converters;

use App\Converters\Models\Currency;

class CurrencyConverter extends Converter
{
    public $xmlRoot = 'currencies';

    public $convertFrom = 'array.json';

    protected $model = Currency::class;

    public $files = [
        [
            'filename' => 'array.json',
            'prettify' => true,
            'except' => '*.symbol',
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
        return base_path('repos/currencies/src');
    }
}