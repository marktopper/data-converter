<?php

namespace App\Converters\Models;

class Currency extends Model
{
    protected $fillable = ['code', 'name'];

    public function getCodeAttribute()
    {
        return strtolower($this->attributes['code']);
    }
}