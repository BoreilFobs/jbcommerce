<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class wishes extends Model
{
    //
    protected $fillable = [
        'user_id',
        'offer_id',
    ];
}
