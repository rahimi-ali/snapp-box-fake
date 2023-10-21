<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $delivery_id
 * @property int $dropOffSequenceNumber
 * @property int $pickedUpSequenceNumber
 * @property string $name
 * @property int $quantity
 * @property string $quantityMeasuringUnit
 * @property int|null $packageValue
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Delivery $delivery
 */
class DeliveryItem extends Model
{
    protected $guarded = [];

    public function delivery(): BelongsTo
    {
        return $this->belongsTo(Delivery::class);
    }
}
