<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ContactStore extends Model
{
    use SoftDeletes;

    protected $fillable = ['user_id','store_id', 'photo', 'name', 'whatsapp', 'ativo'];

    protected $appends = ['photo_url'];

    public function user() {
        return $this->belongsTo(User::class);
    }
    
    public function store() {
        return $this->belongsTo(Store::class);
    }

    public function getPhotoUrlAttribute()
    {
        if(filter_var($this->photo, FILTER_VALIDATE_URL)) {
            return $this->photo;  // Retorna a URL completa caso seja do Cloudinary
        }
        
        return $this->photo ? asset("storage/{$this->photo}") : null;
    }
}
