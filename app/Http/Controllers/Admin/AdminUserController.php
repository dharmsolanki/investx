<?php
// ============================================================
// FILE: app/Http/Controllers/Admin/AdminUserController.php
// ============================================================

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class AdminUserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('is_admin', false)->with('investments');

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(fn($q) => $q->where('name','like',"%$s%")->orWhere('email','like',"%$s%")->orWhere('phone','like',"%$s%"));
        }

        if ($request->filled('kyc')) {
            $query->where('kyc_status', $request->kyc);
        }

        $users = $query->latest()->paginate(20);
        return view('admin.users.index', compact('users'));
    }

    public function show(User $user)
    {
        $user->load('investments.plan', 'transactions', 'withdrawals');
        return view('admin.users.show', compact('user'));
    }

    public function updateKyc(Request $request, User $user)
    {
        $request->validate(['kyc_status' => 'required|in:verified,rejected']);

        $user->update(['kyc_status' => $request->kyc_status]);

        return back()->with('success', "KYC status updated: {$request->kyc_status}");
    }

    public function toggleStatus(User $user)
    {
        $user->update(['is_active' => !$user->is_active]);
        $status = $user->is_active ? 'activated' : 'suspended';
        return back()->with('success', "User {$status} successfully.");
    }
}
