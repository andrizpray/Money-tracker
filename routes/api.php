<?php

use App\Http\Controllers\TelegramBotController;
use Illuminate\Support\Facades\Route;

// Telegram Bot Webhook (public - no session/auth needed)
Route::post('/telegram/webhook', [TelegramBotController::class, 'webhook'])
    ->name('telegram.webhook');

// Telegram webhook management (protected)
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/telegram/set-webhook', [TelegramBotController::class, 'setWebhook'])
        ->name('telegram.set-webhook');
    Route::get('/telegram/webhook-info', [TelegramBotController::class, 'webhookInfo'])
        ->name('telegram.webhook-info');
});
