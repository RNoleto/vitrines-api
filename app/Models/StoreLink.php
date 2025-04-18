<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StoreLink extends Model
{
    protected $fillable = ['store_id', 'icone', 'texto', 'url'];

    public function store() {
        return $this->belongsTo(Store::class);
    }
}
