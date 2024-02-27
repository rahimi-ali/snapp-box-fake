<?php

use App\Http\Controllers\Web\DeliveryController;
use App\Http\Controllers\Web\WebhookController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth.basic')->group(function () {
    Route::get('deliveries', [DeliveryController::class, 'index'])
        ->name('deliveries.index');

    Route::get('deliveries/{maskedId}', [DeliveryController::class, 'show'])
        ->name('deliveries.show');

    Route::post('deliveries/{maskedId}/update-status', [DeliveryController::class, 'updateStatus'])
        ->name('deliveries.update-status');

    Route::post('deliveries/{maskedId}/pay-invoice', [DeliveryController::class, 'payInvoice'])
        ->name('deliveries.pay-invoice');

    Route::resource('webhooks', WebhookController::class)
        ->except(['edit', 'update']);
});
