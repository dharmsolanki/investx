<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InvestmentController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\WalletController;
use App\Http\Controllers\WithdrawalController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\AdminWithdrawalController;
use App\Http\Controllers\Admin\AdminPlanController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\Admin\AdminReviewController;
use App\Http\Controllers\Admin\AdminTransactionController;
use App\Http\Controllers\ContactController;

// ─── Public Routes ─────────────────────────────────────────
Route::get('/', fn() => view('welcome.index'))->name('home');
Route::get('/terms', fn() => view('terms'))->name('terms');
Route::post('/contact', [ContactController::class, 'send'])->name('contact.send');
// Public Pages
Route::get('/about',      fn() => view('pages.about'))->name('about');
Route::get('/privacy',    fn() => view('pages.privacy'))->name('privacy');
Route::get('/refund',     fn() => view('pages.refund'))->name('refund');
Route::get('/contact',    fn() => view('pages.contact'))->name('contact');
Route::get('/grievance',  fn() => view('pages.grievance'))->name('grievance');

// Auth
Route::middleware('guest')->group(function () {
    Route::get('/login',    [LoginController::class,    'showForm'])->name('login');
    Route::post('/login',   [LoginController::class,    'login']);
    Route::get('/register', [RegisterController::class, 'showForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);

    // Forgot Password
    Route::get('/forgot-password',        [ForgotPasswordController::class, 'showForgotForm'])->name('password.request');
    Route::post('/forgot-password',       [ForgotPasswordController::class, 'sendResetLink'])->name('password.email');
    Route::get('/reset-password/{token}', [ForgotPasswordController::class, 'showResetForm'])->name('password.reset.form');
    Route::post('/reset-password',        [ForgotPasswordController::class, 'resetPassword'])->name('password.reset')->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);
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
    // Wallet
    Route::get('/wallet',                    [WalletController::class, 'index'])->name('wallet.index');
    Route::post('/wallet/topup/order',       [WalletController::class, 'topupOrder'])->name('wallet.topup.order');
    Route::post('/wallet/topup/verify',      [WalletController::class, 'topupVerify'])->name('wallet.topup.verify');
    Route::post('/wallet/withdraw',          [WalletController::class, 'withdrawRequest'])->name('wallet.withdraw');

    // Review
    Route::post('/review', [ReviewController::class, 'store'])->name('review.store');
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
        Route::post('/users/{user}/profit', [AdminUserController::class, 'addProfit'])->name('users.profit');
        Route::post('/users/{user}/wallet', [AdminUserController::class, 'adjustWallet'])->name('users.wallet');

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

        // Reviews
        Route::get('/reviews',                    [AdminReviewController::class, 'index'])->name('reviews.index');
        Route::post('/reviews/{review}/approve',  [AdminReviewController::class, 'approve'])->name('reviews.approve');
        Route::post('/reviews/{review}/reject',   [AdminReviewController::class, 'reject'])->name('reviews.reject');

        // Transactions
        Route::get('/transactions', [AdminTransactionController::class, 'index'])->name('transactions.index');
    });
