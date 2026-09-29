<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IconFamily extends Model
{
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'family_name',
        'display_name',
        'provider',
        'import_url',
        'prefix',
        'category',
        'is_system',
        'sample_icons',
    ];

    protected function casts(): array
    {
        return [
            'is_system' => 'boolean',
            'sample_icons' => 'array',
        ];
    }
}
