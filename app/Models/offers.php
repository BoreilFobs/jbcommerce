<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class offers extends Model
{
    //
    protected $fillable = [
        'name',
        'category',
        'price',
        'quantity',
        'image_path',
    ];
}
