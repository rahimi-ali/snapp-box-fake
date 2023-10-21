<?php

use App\Http\Controllers\Api\DeliveryController;
use Illuminate\Support\Facades\Route;

Route::post('/v1/customer/create_order', [DeliveryController::class, 'store'])
    ->name('deliveries.store');
