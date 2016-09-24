<?php

namespace App\Converters\Models;

class Country extends Model
{
    protected static $currencies;

    protected $fillable = ['code', 'name'];

    protected $appends = ['currency'];

    public function getCodeAttribute()
    {
        return strtolower($this->attributes['code']);
    }

    public function getCurrencyAttribute()
    {
        if (is_null(static::$currencies)) {
            static::$currencies = json_decode(file_get_contents(storage_path('app/data/currencies.json')), true);
        }

        $code = strtoupper($this->attributes['code']);

        return strtolower(static::$currencies[$code]);
    }
}