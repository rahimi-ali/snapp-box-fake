<?php

namespace App\Jobs;

use App\Models\Delivery;
use App\Models\WebhookSubscriber;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;

class InvoiceStatusUpdateWebhook implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public readonly Delivery $delivery)
    {
    }

    public function handle(): void
    {
        $payload = [
            'webhookType' => 'INVOICE_STATUS_UPDATE',
            'orderId' => (string) $this->delivery->id,
            'customerRefId' => $this->delivery->customerRefId,
            'invoiceId' => (string) $this->delivery->id,
            'invoiceStatus' => $this->delivery->invoiceStatus,
            'invoiceDirection' => 'CREDITOR',
            'orderStatus' => $this->delivery->status,
            'batch' => $this->delivery->batchable,
        ];

        $webhookSubscribers = WebhookSubscriber::query()->with(['headers'])->get();

        foreach ($webhookSubscribers as $webhookSubscriber) {
            $headers = [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ];

            foreach ($webhookSubscriber->headers as $header) {
                $headers[$header->key] = $header->value;
            }

            try {
                $response = Http::withHeaders($headers)->post($webhookSubscriber->url, $payload);
                if ($response->failed()) {
                    Log::error('Webhook failed', [
                        'url' => $webhookSubscriber->url,
                        'payload' => $payload,
                        'headers' => $headers,
                        'status' => $response->status(),
                        'response' => $response->body(),
                    ]);
                }
            } catch (Throwable $e) {
                Log::error('Webhook failed', [
                    'url' => $webhookSubscriber->url,
                    'payload' => $payload,
                    'headers' => $headers,
                    'exception' => $e->getMessage(),
                ]);
            }
        }
    }
}
