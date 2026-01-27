<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ConversationController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OnboardingController;
use App\Http\Controllers\PlayerProfileController;
use App\Http\Controllers\ScoutProfileController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\StripeWebhookController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('landing');

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');

Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::view('/subscription/required', 'subscription.required')->name('subscription.required');

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

Route::middleware(['auth', 'subscription.active'])->group(function () {
    Route::get('/home', HomeController::class)->name('home');

    // Jogadores
    Route::get('/players', [PlayerProfileController::class, 'index'])->name('players.index');
    Route::get('/players/{user}', [PlayerProfileController::class, 'show'])->name('players.show');
    Route::get('/me/player-profile', [PlayerProfileController::class, 'edit'])->name('me.player-profile.edit');
    Route::post('/me/player-profile', [PlayerProfileController::class, 'update'])->name('me.player-profile.update');

    Route::get('/me/player-videos', [PlayerProfileController::class, 'videos'])->name('me.player-videos');
    Route::post('/me/player-videos', [PlayerProfileController::class, 'storeVideo'])->name('me.player-videos.store');

    Route::get('/me/player-stats', [PlayerProfileController::class, 'stats'])->name('me.player-stats');
    Route::post('/me/player-stats', [PlayerProfileController::class, 'storeStat'])->name('me.player-stats.store');

    // Profissionais
    Route::get('/scouts/{user}', [ScoutProfileController::class, 'show'])->name('scouts.show');
    Route::get('/me/scout-profile', [ScoutProfileController::class, 'edit'])->name('me.scout-profile.edit');
    Route::post('/me/scout-profile', [ScoutProfileController::class, 'update'])->name('me.scout-profile.update');

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
});
