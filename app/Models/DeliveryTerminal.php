<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $delivery_id
 * @property int $sequence
 * @property string $type
 * @property string $paymentType
 * @property string $status
 * @property string $contactName
 * @property string $contactPhoneNumber
 * @property float $latitude
 * @property float $longitude
 * @property string $address
 * @property string|null $comment
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Delivery $delivery
 */
class DeliveryTerminal extends Model
{
    use HasFactory;

    public function delivery(): BelongsTo
    {
        return $this->belongsTo(Delivery::class);
    }
}
