<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('investments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('plan_id');
            $table->decimal('principal_amount', 12, 2);
            $table->decimal('expected_profit', 12, 2)->default(0);
            $table->decimal('commission_amount', 12, 2)->default(0);
            $table->decimal('net_profit', 12, 2)->default(0);       // profit after commission
            $table->decimal('actual_profit', 12, 2)->default(0);     // set by admin at maturity
            $table->enum('status', ['active', 'matured', 'withdrawn', 'cancelled'])->default('active');
            $table->timestamp('invested_at')->useCurrent();
            $table->timestamp('maturity_date')->nullable();
            $table->timestamp('withdrawn_at')->nullable();
            $table->text('admin_notes')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('plan_id')->references('id')->on('investment_plans');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('investments');
    }
};
