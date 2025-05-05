<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contact extends Model
{
    use SoftDeletes;

    protected $fillable = ['user_id', 'photo', 'name', 'whatsapp', 'deleted_at'];

    protected $appends = ['photo_url'];

    public function user() {
        return $this->belongsTo(User::class);
    }
    
    public function stores()
    {
        return $this->belongsToMany(Store::class)
            ->using(ContactStore::class)
            ->withTimestamps()
            ->withPivot('deleted_at');
    }

    public function getPhotoUrlAttribute()
    {
        return $this->photo ? asset("storage/{$this->photo}") : null;
    }

    public function scopeWithTrashedPivots($query)
    {
        return $query->withTrashedPivots();
    }
}
