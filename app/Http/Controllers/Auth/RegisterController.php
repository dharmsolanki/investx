<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function showForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name'                  => 'required|string|max:100',
            'email'                 => 'required|email|unique:users,email',
            'phone'                 => 'required|digits:10|unique:users,phone',
            'password'              => 'required|min:8|confirmed',
            'referral_code'         => 'nullable|exists:users,referral_code',
            'terms'                 => 'accepted',
        ], [
            'email.unique'  => 'Yeh email already registered hai.',
            'phone.unique'  => 'Yeh phone number already registered hai.',
            'terms.accepted' => 'Terms & Conditions accept karna zaroori hai.',
        ]);

        // Find referrer
        $referrer = null;
        if ($request->filled('referral_code')) {
            $referrer = User::where('referral_code', $request->referral_code)->first();
        }

        $user = User::create([
            'name'        => $request->name,
            'email'       => $request->email,
            'phone'       => $request->phone,
            'password'    => Hash::make($request->password),
            'referred_by' => $referrer?->id,
        ]);

        Auth::login($user);

        return redirect()->route('dashboard')->with('success', 'Welcome! Aapka account ban gaya. Ab KYC complete karein.');
    }
}
