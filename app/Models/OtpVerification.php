<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class OtpVerification extends Model
{
    use HasFactory;

    protected $fillable = [
        'phone',
        'code',
        'type',
        'expires_at',
        'verified',
        'verified_at',
        'attempts',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'verified_at' => 'datetime',
        'verified' => 'boolean',
    ];

    /**
     * Vérifier si l'OTP est expiré
     */
    public function isExpired()
    {
        return Carbon::now()->greaterThan($this->expires_at);
    }

    /**
     * Vérifier si l'OTP est valide
     */
    public function isValid()
    {
        return !$this->verified && !$this->isExpired();
    }

    /**
     * Marquer l'OTP comme vérifié
     */
    public function markAsVerified()
    {
        $this->update([
            'verified' => true,
            'verified_at' => Carbon::now(),
        ]);
    }

    /**
     * Incrémenter le nombre de tentatives
     */
    public function incrementAttempts()
    {
        $this->increment('attempts');
    }

    /**
     * Scope pour obtenir les OTPs non vérifiés
     */
    public function scopeUnverified($query)
    {
        return $query->where('verified', false);
    }

    /**
     * Scope pour obtenir les OTPs non expirés
     */
    public function scopeNotExpired($query)
    {
        return $query->where('expires_at', '>', Carbon::now());
    }

    /**
     * Scope pour obtenir les OTPs valides
     */
    public function scopeValid($query)
    {
        return $query->unverified()->notExpired();
    }

    /**
     * Nettoyer les anciens OTPs expirés
     */
    public static function cleanExpiredOtps()
    {
        return static::where('expires_at', '<', Carbon::now()->subDay())
                     ->delete();
    }
}
