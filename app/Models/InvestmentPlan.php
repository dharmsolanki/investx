<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvestmentPlan extends Model
{
    protected $fillable = [
        'name', 'description', 'roi_percent', 'duration_months',
        'min_amount', 'max_amount', 'commission_percent', 'is_active', 'sort_order',
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

    /**
     * Calculate expected profit for a given amount
     */
    public function calculateProfit(float $amount): array
    {
        // Pro-rate ROI for plan duration
        $annualRoi        = $this->roi_percent / 100;
        $periodRoi        = $annualRoi * ($this->duration_months / 12);
        $grossProfit      = $amount * $periodRoi;
        $commissionAmount = $grossProfit * ($this->commission_percent / 100);
        $netProfit        = $grossProfit - $commissionAmount;

        return [
            'principal'         => $amount,
            'gross_profit'      => round($grossProfit, 2),
            'commission_amount' => round($commissionAmount, 2),
            'net_profit'        => round($netProfit, 2),
            'total_return'      => round($amount + $netProfit, 2),
        ];
    }
}
