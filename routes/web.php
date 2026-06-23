<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ConversationController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\OnboardingController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\PlayerProfileController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\ReferralController;
use App\Http\Controllers\ScoutProfileController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\StripeWebhookController;
use Illuminate\Support\Facades\Route;

Route::get('/', LandingController::class)->name('landing');

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');

Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/password/esqueci', [PasswordResetController::class, 'showForgotForm'])->name('password.request');
Route::post('/password/esqueci', [PasswordResetController::class, 'sendResetLink'])->name('password.email');
Route::get('/password/redefinir/{token}', [PasswordResetController::class, 'showResetForm'])->name('password.reset');
Route::post('/password/redefinir', [PasswordResetController::class, 'reset'])->name('password.update');

Route::view('/subscription/required', 'subscription.required')->name('subscription.required');
Route::view('/conta-desativada', 'account.deactivated')->name('account.deactivated');

// Onboarding / pagamento
Route::get('/onboarding/tipo-usuario', [OnboardingController::class, 'chooseUserType'])->name('onboarding.user-type');
Route::post('/onboarding/tipo-usuario', [OnboardingController::class, 'storeUserType'])->name('onboarding.user-type.store');

Route::get('/onboarding/criar-conta', [OnboardingController::class, 'showRegister'])->name('onboarding.register');
Route::post('/onboarding/criar-conta', [OnboardingController::class, 'register'])->name('onboarding.register.post');

Route::middleware('auth')->group(function () {
    Route::get('/onboarding/planos', [OnboardingController::class, 'plans'])->name('onboarding.plans');
    Route::post('/onboarding/checkout', [OnboardingController::class, 'checkout'])->name('onboarding.checkout');
    Route::get('/onboarding/sucesso', [OnboardingController::class, 'success'])->name('onboarding.success');
});

Route::post('/stripe/webhook', StripeWebhookController::class)->name('stripe.webhook');

Route::get('/indicacao/{code}', [ReferralController::class, 'capture'])->name('referral.capture');

// Painel administrativo (apenas usuários com is_admin = true)
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/users', [AdminController::class, 'users'])->name('users');
    Route::get('/users/{user}', [AdminController::class, 'showUser'])->name('users.show');
    Route::post('/users/{user}/toggle-admin', [AdminController::class, 'toggleAdmin'])->name('users.toggle-admin');
    Route::post('/users/{user}/block-referral', [AdminController::class, 'blockReferralProgram'])->name('users.block-referral');
    Route::post('/users/{user}/unblock-referral', [AdminController::class, 'unblockReferralProgram'])->name('users.unblock-referral');
    Route::post('/users/{user}/invalidate-referral', [AdminController::class, 'invalidateReferral'])->name('users.invalidate-referral');
    Route::post('/users/{user}/cancel-plan', [AdminController::class, 'cancelPlan'])->name('users.cancel-plan');
    Route::post('/users/{user}/deactivate', [AdminController::class, 'deactivate'])->name('users.deactivate');
    Route::post('/users/{user}/reactivate', [AdminController::class, 'reactivate'])->name('users.reactivate');

    Route::get('/subscriptions', [AdminController::class, 'subscriptions'])->name('subscriptions');

    Route::get('/referrals', [AdminController::class, 'referrals'])->name('referrals');
    Route::get('/referrals/withdrawals', [AdminController::class, 'referralWithdrawals'])->name('referrals.withdrawals');
    Route::post('/referrals/process-payouts', [AdminController::class, 'processReferralPayouts'])->name('referrals.process-payouts');

    Route::get('/plan-prices', [AdminController::class, 'planPrices'])->name('plan-prices');
    Route::post('/plan-prices', [AdminController::class, 'updatePlanPrices'])->name('plan-prices.update');
    Route::post('/plan-prices/{plan}/toggle', [AdminController::class, 'togglePlanPrice'])->name('plan-prices.toggle');
});

Route::middleware(['auth', 'active', 'subscription.active'])->group(function () {
    Route::get('/home', HomeController::class)->name('home');

    // Jogadores
    Route::get('/players', [PlayerProfileController::class, 'index'])->name('players.index');
    Route::get('/players/{user}', [PlayerProfileController::class, 'show'])->name('players.show');
    Route::get('/me/player-profile', [PlayerProfileController::class, 'edit'])->name('me.player-profile.edit');
    Route::post('/me/player-profile', [PlayerProfileController::class, 'update'])->name('me.player-profile.update');

    Route::get('/me/player-videos', [PlayerProfileController::class, 'videos'])->name('me.player-videos');
    Route::post('/me/player-videos', [PlayerProfileController::class, 'storeVideo'])->name('me.player-videos.store');

    Route::get('/me/player-photos', [PlayerProfileController::class, 'photos'])->name('me.player-photos');
    Route::post('/me/player-photos', [PlayerProfileController::class, 'storePhoto'])->name('me.player-photos.store');
    Route::delete('/me/player-photos/{photo}', [PlayerProfileController::class, 'destroyPhoto'])->name('me.player-photos.destroy');

    Route::get('/me/player-stats', [PlayerProfileController::class, 'stats'])->name('me.player-stats');
    Route::post('/me/player-stats', [PlayerProfileController::class, 'storeStat'])->name('me.player-stats.store');

    // Profissionais
    Route::get('/scouts/{user}', [ScoutProfileController::class, 'show'])->name('scouts.show');
    Route::get('/me/scout-profile', [ScoutProfileController::class, 'edit'])->name('me.scout-profile.edit');
    Route::post('/me/scout-profile', [ScoutProfileController::class, 'update'])->name('me.scout-profile.update');

    Route::get('/me/scout-photos', [ScoutProfileController::class, 'photos'])->name('me.scout-photos');
    Route::post('/me/scout-photos', [ScoutProfileController::class, 'storePhoto'])->name('me.scout-photos.store');
    Route::delete('/me/scout-photos/{photo}', [ScoutProfileController::class, 'destroyPhoto'])->name('me.scout-photos.destroy');

    // Mensagens
    Route::get('/messages', [ConversationController::class, 'index'])->name('messages.index');
    Route::get('/messages/start/{user}', [ConversationController::class, 'start'])->name('messages.start')->where('user', '[0-9]+');
    Route::get('/messages/{conversation}', [ConversationController::class, 'show'])->name('messages.show');
    Route::post('/messages/{conversation}', [ConversationController::class, 'storeMessage'])->name('messages.store');

    // Configurações / plano
    Route::get('/settings/profile', [SettingsController::class, 'profile'])->name('settings.profile');
    Route::post('/settings/profile', [SettingsController::class, 'updateProfile'])->name('settings.profile.update');
    Route::post('/settings/password', [SettingsController::class, 'updatePassword'])->name('settings.password.update');

    Route::get('/settings/plan', [SettingsController::class, 'plan'])->name('settings.plan');
    Route::post('/settings/plan/cancel', [SettingsController::class, 'cancelPlan'])->name('settings.plan.cancel');

    Route::get('/indique-e-ganhe', [ReferralController::class, 'index'])->name('referrals.index');
    Route::get('/indique-e-ganhe/ranking', [ReferralController::class, 'ranking'])->name('referrals.ranking');
    Route::post('/indique-e-ganhe/codigo', [ReferralController::class, 'updateCode'])->name('referrals.code.update');
    Route::post('/indique-e-ganhe/pix', [ReferralController::class, 'updatePix'])->name('referrals.pix.update');

    Route::get('/favoritos', [FavoriteController::class, 'index'])->name('favorites.index');
    Route::post('/favoritos/{player}', [FavoriteController::class, 'toggle'])->name('favorites.toggle');
    Route::post('/settings/account/cancel', [SettingsController::class, 'cancelAccount'])->name('settings.account.cancel');
    Route::post('/settings/account/delete', [SettingsController::class, 'deleteAccount'])->name('settings.account.delete');
});
