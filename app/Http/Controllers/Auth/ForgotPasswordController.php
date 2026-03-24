<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class ForgotPasswordController extends Controller
{
    // Step 1 — Form dikhao
    public function showForgotForm()
    {
        return view('auth.forgot-password');
    }

    // Step 2 — Email se token bhejo
    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ], [
            'email.exists' => 'Yeh email registered nahi hai.',
        ]);

        $token = Str::random(64);

        // Purana token delete karo, naya daalo
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();
        DB::table('password_reset_tokens')->insert([
            'email'      => $request->email,
            'token'      => Hash::make($token),
            'created_at' => now(),
        ]);

        $resetUrl = url('/reset-password/' . $token . '?email=' . urlencode($request->email));

        // Email bhejo
        Mail::send([], [], function ($message) use ($request, $resetUrl) {
            $message->to($request->email)
                ->subject('DailyWealth — Password Reset Request')
                ->html("
                    <div style='font-family:sans-serif;max-width:500px;margin:0 auto;padding:20px'>
                        <h2 style='color:#C9A84C'>DailyWealth Password Reset</h2>
                        <p>Aapne password reset request ki hai.</p>
                        <p>Neeche button click karke naya password set karein:</p>
                        <a href='{$resetUrl}'
                           style='display:inline-block;background:#C9A84C;color:#0A0C10;padding:12px 24px;
                                  border-radius:8px;text-decoration:none;font-weight:700;margin:16px 0'>
                            Reset Password →
                        </a>
                        <p style='color:#888;font-size:0.85rem'>Yeh link 60 minutes mein expire ho jayega.</p>
                        <p style='color:#888;font-size:0.85rem'>Agar aapne yeh request nahi ki toh ignore karein.</p>
                    </div>
                ");
        });

        return back()->with('success', 'Password reset link aapke email par bhej diya gaya hai. 60 minutes mein use karein.');
    }

    // Step 3 — Reset form dikhao
    public function showResetForm(Request $request, $token)
    {
        return view('auth.reset-password', [
            'token' => $token,
            'email' => $request->email,
        ]);
    }

    // Step 4 — Naya password save karo
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email'    => 'required|email|exists:users,email',
            'token'    => 'required',
            'password' => 'required|min:8|confirmed',
        ], [
            'password.confirmed' => 'Dono passwords match nahi karte.',
            'password.min'       => 'Password kam se kam 8 characters ka hona chahiye.',
        ]);

        // Token check karo
        $record = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->first();

        if (!$record || !Hash::check($request->token, $record->token)) {
            return back()->with('error', 'Reset link invalid hai ya expire ho gayi hai.');
        }

        // 60 minute expiry check
        if (now()->diffInMinutes($record->created_at) > 60) {
            DB::table('password_reset_tokens')->where('email', $request->email)->delete();
            return back()->with('error', 'Reset link expire ho gayi hai. Dobara try karein.');
        }

        // Password update karo
        User::where('email', $request->email)->update([
            'password' => Hash::make($request->password),
        ]);

        // Token delete karo
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        return redirect()->route('login')
            ->with('success', 'Password successfully reset ho gaya! Ab login karein.');
    }
}