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
        'icon_family',
        'layout_style',
        'card_style',
        'btn_shape',
        'btn_shadow',
        'avatar_shape',
        'show_social_footer',
        'social_style',
        'elements',
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
            'show_social_footer' => 'boolean',
            'elements' => 'array',
            'bg_overlay' => 'array',
            'colors' => 'array',
            'backdrop_blur' => 'integer',
        ];
    }
}
