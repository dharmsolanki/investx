<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InvestmentController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\WithdrawalController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\AdminWithdrawalController;
use App\Http\Controllers\Admin\AdminPlanController;
use Illuminate\Support\Facades\Route;

// ─── Public Routes ─────────────────────────────────────────
Route::get('/', fn() => view('welcome.index'))->name('home');

// Auth
Route::middleware('guest')->group(function () {
    Route::get('/login',    [LoginController::class,    'showForm'])->name('login');
    Route::post('/login',   [LoginController::class,    'login']);
    Route::get('/register', [RegisterController::class, 'showForm'])->name('register');
    Route::post('/register',[RegisterController::class, 'register']);
});

Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

// ─── User Routes (auth required) ───────────────────────────
Route::middleware(['auth'])->group(function () {

    // Dashboard
    Route::get('/dashboard',         [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/kyc',               [DashboardController::class, 'kyc'])->name('kyc');
    Route::post('/kyc',              [DashboardController::class, 'updateKyc'])->name('kyc.update');

    // Investment Plans & Investing
    Route::get('/plans',             [InvestmentController::class, 'plans'])->name('plans');
    Route::get('/invest/{plan}',     [InvestmentController::class, 'showInvestForm'])->name('investments.form');
    Route::post('/invest',           [InvestmentController::class, 'store'])->name('investments.store');
    Route::get('/my-investments',    [InvestmentController::class, 'myInvestments'])->name('investments.my');
    Route::get('/calculate-profit',  [InvestmentController::class, 'calculate'])->name('investments.calculate');

    // Payments (Razorpay)
    Route::post('/payment/create-order',  [PaymentController::class, 'createOrder'])->name('payment.order');
    Route::post('/payment/verify',        [PaymentController::class, 'verifyPayment'])->name('payment.verify');

    // Withdrawals
    Route::get('/withdrawals',            [WithdrawalController::class, 'index'])->name('withdrawals.index');
    Route::post('/withdrawals/request',   [WithdrawalController::class, 'request'])->name('withdrawals.request');
});

// Razorpay webhook (no CSRF, no auth)
Route::post('/payment/webhook', [PaymentController::class, 'webhook'])
    ->name('payment.webhook')
    ->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);

// ─── Admin Routes ───────────────────────────────────────────
Route::middleware(['auth', \App\Http\Middleware\AdminMiddleware::class])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('/',          [AdminDashboardController::class, 'index'])->name('dashboard');

        // Users
        Route::get('/users',                [AdminUserController::class, 'index'])->name('users.index');
        Route::get('/users/{user}',         [AdminUserController::class, 'show'])->name('users.show');
        Route::post('/users/{user}/kyc',    [AdminUserController::class, 'updateKyc'])->name('users.kyc');
        Route::post('/users/{user}/toggle', [AdminUserController::class, 'toggleStatus'])->name('users.toggle');

        // Plans
        Route::get('/plans',                [AdminPlanController::class, 'index'])->name('plans.index');
        Route::get('/plans/create',         [AdminPlanController::class, 'create'])->name('plans.create');
        Route::post('/plans',               [AdminPlanController::class, 'store'])->name('plans.store');
        Route::get('/plans/{plan}/edit',    [AdminPlanController::class, 'edit'])->name('plans.edit');
        Route::put('/plans/{plan}',         [AdminPlanController::class, 'update'])->name('plans.update');
        Route::post('/plans/{plan}/toggle', [AdminPlanController::class, 'toggleStatus'])->name('plans.toggle');

        // Withdrawals
        Route::get('/withdrawals',                        [AdminWithdrawalController::class, 'index'])->name('withdrawals.index');
        Route::post('/withdrawals/{withdrawal}/approve',  [AdminWithdrawalController::class, 'approve'])->name('withdrawals.approve');
        Route::post('/withdrawals/{withdrawal}/reject',   [AdminWithdrawalController::class, 'reject'])->name('withdrawals.reject');
    });
