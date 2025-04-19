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
        return $this->logo ? asset("storage/{$this->logo}") : null;
    }
}
