<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ContactStores extends Model
{
    use SoftDeletes;

    protected $fillable = ['store_id', 'photo', 'name', 'whatsapp', 'ativo'];

    protected $appends = ['photo_url'];

    public function store() {
        return $this->belongsTo(Store::class);
    }

    public function getPhotoUrlAttribute()
    {
        return $this->photo ? asset("storage/{$this->photo}") : null;
    }
}
