<?php

namespace App\Jobs;

use App\Models\WebhookSubscriber;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;

/**
 * @method static void dispatch(WebhookSubscriber $webhookSubscriber, array $data)
 */
class CallWebhook implements ShouldQueue
{
    use Dispatchable, Queueable, SerializesModels;

    public int $tries = 50;

    public int $backoff = 5;

    public function __construct(
        public readonly WebhookSubscriber $webhookSubscriber,
        public readonly array $data
    ) {
    }

    public function handle(): void
    {
        $headers = [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ];

        foreach ($this->webhookSubscriber->headers as $header) {
            $headers[$header->name] = $header->value;
        }

        try {
            $response = Http::withHeaders($headers)->post($this->webhookSubscriber->url, $this->data);
            if ($response->failed()) {
                Log::error('Webhook failed', [
                    'url' => $this->webhookSubscriber->url,
                    'payload' => $this->data,
                    'headers' => $headers,
                    'status' => $response->status(),
                    'response' => $response->body(),
                ]);
            }
        } catch (Throwable $e) {
            Log::error('Webhook failed', [
                'url' => $this->webhookSubscriber->url,
                'payload' => $this->data,
                'headers' => $headers,
                'exception' => $e->getMessage(),
            ]);

            throw $e;
        }
    }
}
