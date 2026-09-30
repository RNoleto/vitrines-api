<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;
use App\Models\Contact;
use Illuminate\Support\Str;


use App\Models\Theme;

class Store extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id', 'name', 'slug', 'logo', 'ativo', 'ref_cod_theme', 
        'description', 'bio', 'metrics', 'faqs', 'social_networks', 'show_social_footer'
    ];

    protected $casts = [
        'metrics'            => 'array',
        'faqs'               => 'array',
        'social_networks'    => 'array',
        'show_social_footer' => 'integer',
        'ref_cod_theme'      => 'integer',
    ];

    protected $appends = ['logo_url'];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function theme() {
        return $this->belongsTo(Theme::class, 'ref_cod_theme');
    }

    public function links() {
        return $this->hasMany(StoreLink::class);
    }

    public function getLogoUrlAttribute()
    {
        // Verifica se a logo já é uma URL completa (do Cloudinary ou outro serviço externo)
        if (filter_var($this->logo, FILTER_VALIDATE_URL)) {
            return $this->logo;  // Retorna a URL completa caso seja do Cloudinary
        }

        // Se não for uma URL completa, então assume que é uma imagem armazenada localmente
        return $this->logo ? asset("storage/{$this->logo}") : null;
    }

    public function contacts()
    {
        return $this->belongsToMany(Contact::class)
            ->using(ContactStore::class)
            ->withTimestamps()
            ->withPivot('deleted_at')
            ->wherePivotNull('deleted_at');
    }

    public function getRouteKeyName()
    {
        return 'slug'; // Usar slug ao invés de id para binding
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->slug)) {
                $model->slug = Str::slug($model->name);
            }
        });
    }
}
