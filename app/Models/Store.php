<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Store extends Model
{
    use SoftDeletes;

    protected $fillable = ['user_id', 'name', 'logo', 'ativo'];

    protected $appends = ['logo_url'];

    public function user() {
        return $this->belongsTo(User::class);
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
        return $this->hasMany(Contact::class);
    }

}
