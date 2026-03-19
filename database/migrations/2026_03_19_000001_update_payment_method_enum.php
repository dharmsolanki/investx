<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE transactions MODIFY COLUMN payment_method 
            ENUM('upi','netbanking','card','imps','neft','crypto','paytm','wallet','razorpay','bank_transfer') 
            NULL");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE transactions MODIFY COLUMN payment_method 
            ENUM('upi','netbanking','card','imps','neft','crypto','paytm','wallet') 
            NULL");
    }
};