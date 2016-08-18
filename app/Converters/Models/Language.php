<?php

namespace App\Converters\Models;

class Language extends Model
{
    protected $fillable = ['code', 'name', 'nativeName'];

    public function getCodeAttribute()
    {
        return strtolower($this->attributes['code']);
    }
}