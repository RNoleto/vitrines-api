<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Theme extends Model
{
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'label',
        'is_custom',
        'is_premium',
        'category',
        'font_family',
        'layout_style',
        'card_style',
        'bg_type',
        'bg_image_url',
        'bg_attachment',
        'bg_size',
        'bg_position',
        'bg_animation_type',
        'bg_overlay',
        'colors',
        'backdrop_blur',
    ];

    protected function casts(): array
    {
        return [
            'is_custom' => 'boolean',
            'is_premium' => 'boolean',
            'bg_overlay' => 'array',
            'colors' => 'array',
            'backdrop_blur' => 'integer',
        ];
    }
}
