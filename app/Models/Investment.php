<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Investment extends Model
{
    protected $fillable = [
        'user_id', 'plan_id', 'principal_amount', 'expected_profit',
        'commission_amount', 'net_profit', 'actual_profit',
        'status', 'invested_at', 'maturity_date', 'withdrawn_at', 'admin_notes',
    ];

    protected $casts = [
        'principal_amount'  => 'decimal:2',
        'expected_profit'   => 'decimal:2',
        'commission_amount' => 'decimal:2',
        'net_profit'        => 'decimal:2',
        'actual_profit'     => 'decimal:2',
        'invested_at'       => 'datetime',
        'maturity_date'     => 'datetime',
        'withdrawn_at'      => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function plan()
    {
        return $this->belongsTo(InvestmentPlan::class, 'plan_id');
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function withdrawal()
    {
        return $this->hasOne(Withdrawal::class);
    }

    // Scopes
    public function scopeActive($q)      { return $q->where('status', 'active'); }
    public function scopeMatured($q)     { return $q->where('status', 'matured'); }
    public function scopeWithdrawn($q)   { return $q->where('status', 'withdrawn'); }

    // Helpers
    public function daysRemaining(): int
    {
        if (!$this->maturity_date || $this->status !== 'active') return 0;
        return max(0, now()->diffInDays($this->maturity_date, false));
    }

    public function progressPercent(): int
    {
        if (!$this->maturity_date) return 0;
        $total   = $this->invested_at->diffInDays($this->maturity_date);
        $elapsed = $this->invested_at->diffInDays(now());
        return min(100, (int) (($elapsed / max(1, $total)) * 100));
    }

    public function isMatured(): bool
    {
        return $this->maturity_date && now()->gte($this->maturity_date);
    }
}
