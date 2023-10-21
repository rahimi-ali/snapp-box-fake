<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $url
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Collection<int, WebhookSubscriberHeader> $headers
 */
class WebhookSubscriber extends Model
{
    protected $guarded = [];

    public function headers(): HasMany
    {
        return $this->hasMany(WebhookSubscriberHeader::class);
    }
}
