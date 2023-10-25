<?php

namespace App\Jobs;

use App\Models\Delivery;
use App\Services\WebhookService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class InvoiceStatusUpdateWebhook implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public readonly Delivery $delivery)
    {
    }

    public function handle(WebhookService $webhookService): void
    {
        $payload = [
            'webhookType' => 'INVOICE_STATUS_UPDATE',
            'orderId' => (string)$this->delivery->id,
            'customerRefId' => $this->delivery->customerRefId,
            'invoiceId' => (string)$this->delivery->id,
            'invoiceStatus' => $this->delivery->invoiceStatus,
            'invoiceDirection' => 'CREDITOR',
            'orderStatus' => $this->delivery->status,
            'batch' => $this->delivery->batchable,
        ];

        $webhookService->callAllSubscribers($payload);
    }
}
