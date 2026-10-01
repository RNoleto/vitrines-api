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
        if (empty($this->photo)) {
            return null;
        }

        if (\Illuminate\Support\Str::contains($this->photo, 'cloudinary.com')) {
            return $this->photo;
        }

        $path = $this->photo;

        if (preg_match('#storage/(.+)$#i', $path, $matches)) {
            $path = $matches[1];
        }

        $url = asset("storage/{$path}");

        if (request()->secure() || app()->environment('production') || request()->header('X-Forwarded-Proto') === 'https') {
            $url = \Illuminate\Support\Str::replaceFirst('http://', 'https://', $url);
        }

        return $url;
    }
}
