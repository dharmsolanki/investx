<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Encrypted strings 300-500 chars ki hoti hain — TEXT use karo
            $table->text('pan_number')->nullable()->change();
            $table->text('aadhar_number')->nullable()->change();
            $table->text('bank_account')->nullable()->change();
            $table->text('bank_ifsc')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('pan_number', 10)->nullable()->change();
            $table->string('aadhar_number', 12)->nullable()->change();
            $table->string('bank_account')->nullable()->change();
            $table->string('bank_ifsc')->nullable()->change();
        });
    }
};