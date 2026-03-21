<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'rating'  => 'required|integer|min:1|max:5',
            'comment' => 'required|string|min:10|max:500',
        ]);

        // Ek user sirf ek review de sakta hai
        $existing = Review::where('user_id', Auth::id())->first();
        if ($existing) {
            return back()->with('success', 'Aapka review submit ho gaya! Shukriya.');
            // User ko pata nahi chalega ki already exist karta hai
        }

        Review::create([
            'user_id' => Auth::id(),
            'rating'  => $request->rating,
            'comment' => $request->comment,
            'status'  => 'pending', // silently pending
        ]);

        // User ko lagega submitted — koi "pending" mention nahi
        return back()->with('success', 'Aapka review submit ho gaya! Shukriya. 🙏');
    }
}