<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Font extends Model
{
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'family_name',
        'display_name',
        'provider',
        'import_url',
        'category',
        'is_system',
    ];

    protected function casts(): array
    {
        return [
            'is_system' => 'boolean',
        ];
    }
}
