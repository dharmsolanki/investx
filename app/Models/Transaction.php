<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'user_id', 'investment_id', 'type', 'amount',
        'payment_method', 'payment_id', 'gateway_order_id',
        'status', 'notes', 'gateway_response',
    ];

    protected $casts = [
        'amount'           => 'decimal:2',
        'gateway_response' => 'array',
    ];

    public function user()       { return $this->belongsTo(User::class); }
    public function investment() { return $this->belongsTo(Investment::class); }

    public function scopeCompleted($q) { return $q->where('status', 'completed'); }
    public function scopePending($q)   { return $q->where('status', 'pending'); }

    public function typeBadgeColor(): string
    {
        return match($this->type) {
            'deposit'         => 'blue',
            'profit'          => 'green',
            'withdrawal'      => 'orange',
            'commission'      => 'red',
            'referral_bonus'  => 'purple',
            default           => 'gray',
        };
    }
}
