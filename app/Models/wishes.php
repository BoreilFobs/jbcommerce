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

    /**
     * Get the offer that belongs to the wishlist item.
     */
    public function offer()
    {
        return $this->belongsTo(offers::class, 'offer_id');
    }

    /**
     * Get the user that owns the wishlist item.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
