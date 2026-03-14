<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class EncryptKycData extends Command
{
    protected $signature   = 'kyc:encrypt';
    protected $description = 'Existing plaintext KYC data ko encrypt karo';

    public function handle()
    {
        $this->info('🔐 KYC data encryption shuru ho rahi hai...');

        // Directly DB query use karo — Model casts bypass karne ke liye
        $users = DB::table('users')
            ->whereNotNull('aadhar_number')
            ->orWhereNotNull('pan_number')
            ->orWhereNotNull('bank_account')
            ->get();

        if ($users->isEmpty()) {
            $this->warn('Koi data nahi mila encrypt karne ke liye.');
            return;
        }

        $this->info("Total {$users->count()} users mila.");
        $bar = $this->output->createProgressBar($users->count());
        $bar->start();

        $successCount = 0;
        $skipCount    = 0;

        foreach ($users as $user) {
            $update = [];

            // Aadhar encrypt karo — already encrypted nahi hai to
            if ($user->aadhar_number && !$this->isEncrypted($user->aadhar_number)) {
                $update['aadhar_number'] = Crypt::encryptString($user->aadhar_number);
            }

            // PAN encrypt karo
            if ($user->pan_number && !$this->isEncrypted($user->pan_number)) {
                $update['pan_number'] = Crypt::encryptString($user->pan_number);
            }

            // Bank account encrypt karo
            if ($user->bank_account && !$this->isEncrypted($user->bank_account)) {
                $update['bank_account'] = Crypt::encryptString($user->bank_account);
            }

            if (!empty($update)) {
                DB::table('users')->where('id', $user->id)->update($update);
                $successCount++;
            } else {
                $skipCount++;
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);
        $this->info("✅ {$successCount} users ka data encrypt ho gaya!");

        if ($skipCount > 0) {
            $this->warn("⏭️  {$skipCount} users skip kiye (already encrypted ya empty).");
        }

        $this->info('Ab User model mein encrypted casts add karo.');
    }

    /**
     * Check karo ki value already encrypted hai ya nahi
     * Laravel encrypted strings "eyJpdi..." se shuru hoti hain (base64 JSON)
     */
    private function isEncrypted(string $value): bool
    {
        try {
            Crypt::decryptString($value);
            return true; // decrypt ho gayi — already encrypted thi
        } catch (\Exception $e) {
            return false; // decrypt nahi hui — plaintext hai
        }
    }
}