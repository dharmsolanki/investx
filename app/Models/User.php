<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'phone', 'password', 'pan_number',
        'aadhar_number', 'kyc_status', 'wallet_balance',
        'bank_account', 'bank_ifsc', 'bank_name',
        'referral_code', 'referred_by', 'is_admin', 'is_active',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password'          => 'hashed',
        'wallet_balance'    => 'decimal:2',
        'is_admin'          => 'boolean',
        'is_active'         => 'boolean',
    ];

    // Auto-generate referral code on create
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($user) {
            $user->referral_code = strtoupper(Str::random(8));
        });
    }

    // --- Relationships ---
    public function investments()
    {
        return $this->hasMany(Investment::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function withdrawals()
    {
        return $this->hasMany(Withdrawal::class);
    }

    public function referrer()
    {
        return $this->belongsTo(User::class, 'referred_by');
    }

    public function referrals()
    {
        return $this->hasMany(User::class, 'referred_by');
    }

    // --- Helpers ---
    public function activeInvestments()
    {
        return $this->investments()->where('status', 'active');
    }

    public function totalInvested()
    {
        return $this->investments()->whereIn('status', ['active', 'matured'])->sum('principal_amount');
    }

    public function totalProfit()
    {
        return $this->investments()->where('status', 'withdrawn')->sum('net_profit');
    }

    public function isKycVerified()
    {
        return $this->kyc_status === 'verified';
    }

    public function isAdmin()
    {
        return $this->is_admin;
    }
}
