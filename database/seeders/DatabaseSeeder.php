<?php
// ============================================================
// FILE: database/seeders/DatabaseSeeder.php
// ============================================================

namespace Database\Seeders;

use App\Models\User;
use App\Models\InvestmentPlan;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create Admin
        User::create([
            'name'        => 'Admin',
            'email'       => 'admin@investx.com',
            'phone'       => '9000000000',
            'password'    => Hash::make('Admin@123'),
            'kyc_status'  => 'verified',
            'is_admin'    => true,
            'is_active'   => true,
        ]);

        // Demo User
        User::create([
            'name'        => 'Demo User',
            'email'       => 'demo@investx.com',
            'phone'       => '9111111111',
            'password'    => Hash::make('Demo@1234'),
            'kyc_status'  => 'verified',
            'bank_account' => '123456789012',
            'bank_ifsc'   => 'SBIN0001234',
            'bank_name'   => 'State Bank of India',
            'is_active'   => true,
        ]);

        // Investment Plans
        $plans = [
            [
                'name'               => 'Starter Plan',
                'description'        => 'Beginners ke liye perfect. Chhoti raashi se shuru karein aur platform samjhein.',
                'roi_percent'        => 18.00,
                'duration_months'    => 3,
                'min_amount'         => 1000.00,
                'max_amount'         => 49999.00,
                'commission_percent' => 20.00,
                'sort_order'         => 1,
            ],
            [
                'name'               => 'Growth Plan',
                'description'        => 'Most popular plan. Diversified portfolio mein investment. Best risk-reward ratio.',
                'roi_percent'        => 25.00,
                'duration_months'    => 6,
                'min_amount'         => 10000.00,
                'max_amount'         => 499999.00,
                'commission_percent' => 17.00,
                'sort_order'         => 2,
            ],
            [
                'name'               => 'Elite Plan',
                'description'        => 'Premium investors ke liye. High-value portfolio with dedicated manager.',
                'roi_percent'        => 32.00,
                'duration_months'    => 12,
                'min_amount'         => 100000.00,
                'max_amount'         => null,
                'commission_percent' => 15.00,
                'sort_order'         => 3,
            ],
        ];

        foreach ($plans as $plan) {
            InvestmentPlan::create($plan);
        }

        $this->command->info('✅ Seeding complete!');
        $this->command->info('Admin: admin@investx.com / Admin@123');
        $this->command->info('Demo:  demo@investx.com  / Demo@1234');
    }
}
