<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contact extends Model
{
    use SoftDeletes;

    protected $fillable = ['user_id', 'photo', 'name', 'whatsapp', 'ativo', 'deleted_at'];

    protected $appends = ['photo_url'];

    public function user() {
        return $this->belongsTo(User::class);
    }
    
    public function stores()
    {
        return $this->belongsToMany(Store::class)
            ->using(ContactStore::class)
            ->withTimestamps()
            ->withPivot(['id', 'deleted_at']);
    }

    public function getPhotoUrlAttribute()
    {
        // Verifica se a foto já é uma URL completa (do Cloudinary ou outro serviço externo)
        if (filter_var($this->photo, FILTER_VALIDATE_URL)) {
            return $this->photo;
        }

        // Se não for uma URL completa, assume que é imagem local
        return $this->photo ? asset("storage/{$this->photo}") : null;
    }
}
