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
                'name'               => 'Plan 1',
                'description'        => 'Invest ₹15,000 aur plan summary mein ₹750 daily earning highlight ke saath shuru karein.',
                'roi_percent'        => 18.00,
                'duration_months'    => 3,
                'min_amount'         => 15000.00,
                'max_amount'         => 29999.00,
                'commission_percent' => 20.00,
                'sort_order'         => 1,
            ],
            [
                'name'               => 'Plan 2',
                'description'        => 'Invest ₹30,000 aur ₹1,800 daily earning highlight ke saath regular participation ke liye.',
                'roi_percent'        => 25.00,
                'duration_months'    => 6,
                'min_amount'         => 30000.00,
                'max_amount'         => 59999.00,
                'commission_percent' => 17.00,
                'sort_order'         => 2,
            ],
            [
                'name'               => 'Plan 3',
                'description'        => 'Invest ₹60,000 aur ₹3,600 daily earning highlight ke saath growth-focused members ke liye.',
                'roi_percent'        => 28.00,
                'duration_months'    => 9,
                'min_amount'         => 60000.00,
                'max_amount'         => 99999.00,
                'commission_percent' => 16.00,
                'sort_order'         => 3,
            ],
            [
                'name'               => 'Plan 4',
                'description'        => 'Invest ₹1,00,000 aur ₹7,000 daily earning highlight ke saath premium level access paayein.',
                'roi_percent'        => 32.00,
                'duration_months'    => 12,
                'min_amount'         => 100000.00,
                'max_amount'         => null,
                'commission_percent' => 15.00,
                'sort_order'         => 4,
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
