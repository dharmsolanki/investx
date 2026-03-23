<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Investments — status filter bahut zyada use hota hai
        Schema::table('investments', function (Blueprint $table) {
            $table->index('status');
            $table->index('maturity_date');
            $table->index(['user_id', 'status']);
        });

        // Transactions — type aur status filter frequent hain
        Schema::table('transactions', function (Blueprint $table) {
            $table->index('type');
            $table->index('status');
            $table->index(['user_id', 'type']);
            $table->index(['type', 'status']);
            $table->index('payment_id');
            $table->index('created_at');
        });

        // Withdrawals
        Schema::table('withdrawals', function (Blueprint $table) {
            $table->index('status');
            $table->index(['user_id', 'status']);
        });

        // Users
        Schema::table('users', function (Blueprint $table) {
            $table->index('kyc_status');
            $table->index('is_active');
        });
    }

    public function down(): void
    {
        Schema::table('investments', function (Blueprint $table) {
            $table->dropIndex(['status']);
            $table->dropIndex(['maturity_date']);
            $table->dropIndex(['user_id', 'status']);
        });
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropIndex(['type']);
            $table->dropIndex(['status']);
            $table->dropIndex(['user_id', 'type']);
            $table->dropIndex(['type', 'status']);
            $table->dropIndex(['payment_id']);
            $table->dropIndex(['created_at']);
        });
        Schema::table('withdrawals', function (Blueprint $table) {
            $table->dropIndex(['status']);
            $table->dropIndex(['user_id', 'status']);
        });
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['kyc_status']);
            $table->dropIndex(['is_active']);
        });
    }
};