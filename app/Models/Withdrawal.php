<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Withdrawal extends Model
{
    protected $fillable = [
        'user_id', 'investment_id', 'principal_amount', 'net_profit',
        'total_amount', 'bank_account', 'bank_ifsc', 'bank_name',
        'utr_number', 'status', 'rejection_reason', 'processed_at', 'processed_by',
    ];

    protected $casts = [
        'principal_amount' => 'decimal:2',
        'net_profit'       => 'decimal:2',
        'total_amount'     => 'decimal:2',
        'processed_at'     => 'datetime',
    ];

    public function user()       { return $this->belongsTo(User::class); }
    public function investment() { return $this->belongsTo(Investment::class); }
    public function processor()  { return $this->belongsTo(User::class, 'processed_by'); }

    public function scopePending($q)    { return $q->where('status', 'pending'); }
    public function scopeCompleted($q)  { return $q->where('status', 'completed'); }
}
