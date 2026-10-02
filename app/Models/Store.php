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
        'description', 'subtitle', 'banner_image', 'show_banner', 'bio', 'metrics', 'show_metrics', 'faqs', 'social_networks', 'show_social_footer'
    ];

    protected $casts = [
        'metrics'            => 'array',
        'show_metrics'       => 'integer',
        'show_banner'        => 'integer',
        'faqs'               => 'array',
        'social_networks'    => 'array',
        'show_social_footer' => 'integer',
        'ref_cod_theme'      => 'integer',
    ];

    protected $appends = ['logo_url', 'theme'];

    public function getThemeAttribute()
    {
        return $this->attributes['ref_cod_theme'] ?? null;
    }

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
        if (empty($this->logo)) {
            return null;
        }

        // Se a logo for uma URL externa ou Cloudinary (ex: https://res.cloudinary.com/...)
        if (Str::contains($this->logo, 'cloudinary.com') || (Str::startsWith($this->logo, 'http') && !Str::contains($this->logo, '127.0.0.1') && !Str::contains($this->logo, 'localhost'))) {
            return $this->logo;
        }

        $path = $this->logo;

        // Se o valor salvo no banco contiver URL antiga/local (ex: http://127.0.0.1:8000/storage/logos/abc.jpg)
        if (preg_match('#storage/(.+)$#i', $path, $matches)) {
            $path = $matches[1];
        }

        // Se for uma requisição HTTP ativa no servidor, usar o Host da requisição
        if (request()->hasHeader('host')) {
            $scheme = (request()->secure() || app()->environment('production') || request()->header('X-Forwarded-Proto') === 'https') ? 'https' : request()->getScheme();
            $host = request()->header('host');
            return "{$scheme}://{$host}/storage/{$path}";
        }

        $url = asset("storage/{$path}");

        if (request()->secure() || app()->environment('production') || request()->header('X-Forwarded-Proto') === 'https') {
            $url = Str::replaceFirst('http://', 'https://', $url);
        }

        return $url;
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
