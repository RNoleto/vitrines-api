<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\Pivot;

class ContactStore extends Pivot
{
    use SoftDeletes;
    
    protected $table = 'contact_store';
    protected $dates = ['deleted_at'];
}
