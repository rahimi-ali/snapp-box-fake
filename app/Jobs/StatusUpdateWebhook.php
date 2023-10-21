<?php

namespace App\Jobs;

use App\Models\Delivery;
use App\Models\WebhookSubscriber;
use Faker\Factory;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;

class StatusUpdateWebhook implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public readonly Delivery $delivery)
    {
    }

    public function handle(): void
    {
        $faker = Factory::create('fa_IR');

        $payload = [
            'orderId' => $this->delivery->id,
            'customerRefId' => $this->delivery->customerRefId,
            'orderStatus' => $this->delivery->status,
            'batch' => $this->delivery->batchable,
        ];

        if ($this->delivery->status === 'PENDING') {
            $payload['webhookType'] = 'CANCEL_ALLOCATION';
            $payload['sequenceNumber'] = $faker->randomNumber(); // TODO ?
        } else if ($this->delivery->status === 'CANCELLED') {
            $payload['webhookType'] = 'ORDER_CANCELLED';
            $payload['actionBy'] = $faker->safeEmail();
        } else {
            $payload['webhookType'] = 'ORDER_ACCEPTED';
            $payload['sequenceNumber'] = $faker->randomNumber(); // TODO ?
            $payload['bikerId'] = $this->delivery->bikerId;
            $payload['bikerName'] = $this->delivery->bikerName;
            $payload['bikerPhone'] = $this->delivery->bikerPhoneNumber;
            $payload['bikerPhotoUrl'] = $this->delivery->bikerPhotoUrl;
            $payload['orderAcceptedAt'] = $this->delivery->updated_at->format('Y-m-d H:i:s');
            $payload['latitude'] = 35.6998107; // TODO ?
            $payload['longitude'] = 51.2231463; // TODO ?
        }

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
