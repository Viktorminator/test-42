<?php

use App\Http\Controllers\TariffController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SubscriptionController;

Route::apiResource('subscriptions', SubscriptionController::class);
Route::get('tariffs', [TariffController::class, 'index']);
