<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvestmentPlan extends Model
{
    protected $fillable = [
        'name',
        'description',
        'roi_percent',
        'duration_months',
        'min_amount',
        'max_amount',
        'commission_percent',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'roi_percent'        => 'decimal:2',
        'min_amount'         => 'decimal:2',
        'max_amount'         => 'decimal:2',
        'commission_percent' => 'decimal:2',
        'is_active'          => 'boolean',
    ];

    public function investments()
    {
        return $this->hasMany(Investment::class, 'plan_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('sort_order');
    }


    public function displayDailyEarning(): float
    {
        return match ((int) $this->min_amount) {
            15000  => 750.0,
            30000  => 1800.0,
            60000  => 3600.0,
            100000 => 7000.0,
            default => round($this->min_amount * 0.05, 2),
        };
    }

    public function displayDailyEarningFormatted(): string
    {
        return '₹' . number_format($this->displayDailyEarning(), 0);
    }

    /**
     * Calculate expected profit for a given amount
     */
    public function calculateProfit(float $amount): array
    {
        $dailyEarning    = ($amount / $this->min_amount) * $this->displayDailyEarning();
        $dailyFee        = $dailyEarning * ($this->commission_percent / 100);
        $netDailyEarning = $dailyEarning - $dailyFee;
        $days            = $this->duration_months * 30;
        $grossEarnings   = round($dailyEarning * $days, 2);
        $commissionTotal = round($dailyFee * $days, 2);
        $netEarnings     = round($netDailyEarning * $days, 2);

        return [
            'principal'         => $amount,
            'gross_profit'      => $grossEarnings,
            'commission_amount' => $commissionTotal,
            'net_profit'        => $netEarnings,
            'total_return'      => round($amount + $netEarnings, 2),
        ];
    }
}
