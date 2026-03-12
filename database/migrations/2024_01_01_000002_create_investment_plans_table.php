<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('investment_plans', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('roi_percent', 5, 2);         // e.g. 25.00 = 25% annual
            $table->integer('duration_months');            // lock-in period
            $table->decimal('min_amount', 12, 2);
            $table->decimal('max_amount', 12, 2)->nullable();
            $table->decimal('commission_percent', 5, 2);   // our cut from profit
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('investment_plans');
    }
};
