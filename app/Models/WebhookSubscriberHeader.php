<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $webhook_subscriber_id
 * @property string $name
 * @property string $value
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property WebhookSubscriber $subscriber
 */
class WebhookSubscriberHeader extends Model
{
    protected $guarded = [];

    public function subscriber(): BelongsTo
    {
        return $this->belongsTo(WebhookSubscriber::class);
    }
}
