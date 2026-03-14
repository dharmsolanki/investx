<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('pan_image')->nullable()->after('pan_number');
            $table->string('aadhar_front_image')->nullable()->after('aadhar_number');
            $table->string('aadhar_back_image')->nullable()->after('aadhar_front_image');
            $table->string('bank_passbook_image')->nullable()->after('bank_name');
            $table->text('kyc_rejection_reason')->nullable()->after('kyc_status');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'pan_image', 'aadhar_front_image', 'aadhar_back_image',
                'bank_passbook_image', 'kyc_rejection_reason'
            ]);
        });
    }
};
