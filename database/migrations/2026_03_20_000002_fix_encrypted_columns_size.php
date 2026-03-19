<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE users MODIFY COLUMN pan_number TEXT NULL");
        DB::statement("ALTER TABLE users MODIFY COLUMN aadhar_number TEXT NULL");
        DB::statement("ALTER TABLE users MODIFY COLUMN bank_account TEXT NULL");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE users MODIFY COLUMN pan_number VARCHAR(10) NULL");
        DB::statement("ALTER TABLE users MODIFY COLUMN aadhar_number VARCHAR(12) NULL");
        DB::statement("ALTER TABLE users MODIFY COLUMN bank_account VARCHAR(255) NULL");
    }
};