<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class team extends Model
{
    //
    protected $fillable = [
        'name',
        'role',
        'description',
        'image_path',
        'facebook',
        'whatsapp',
    ];
}
