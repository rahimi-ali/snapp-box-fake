<?php

namespace App\Services;

use App\Jobs\CallWebhook;
use App\Models\WebhookSubscriber;
use Illuminate\Support\Collection;

class WebhookService
{
    /** @param array<string, mixed> $data */
    public function callAllSubscribers(array $data): void
    {
        /** @var Collection<int, WebhookSubscriber> $webhookSubscribers */
        $webhookSubscribers = WebhookSubscriber::query()->with(['headers'])->get();

        foreach ($webhookSubscribers as $webhookSubscriber) {
            CallWebhook::dispatch($webhookSubscriber, $data);
        }
    }
}
