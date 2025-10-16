<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Categorie extends Model
{
    protected $fillable = [
        'name',
        'image_path'
    ];

    public function offers()
    {
        return $this->hasMany(offers::class, 'category', 'name');
    }
}
