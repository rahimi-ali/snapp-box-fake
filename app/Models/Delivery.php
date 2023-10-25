<?php

namespace App\Models;

use App\Jobs\DeliveryStatusUpdateWebhook;
use App\Jobs\InvoiceStatusUpdateWebhook;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property int $customerId
 * @property string $maskedId
 * @property string $status
 * @property string $city
 * @property string $customerRefId
 * @property string $customerName
 * @property string|null $customerPhoneNumber
 * @property string|null $customerEmail
 * @property string $deliveryCategory
 * @property string $deliveryFarePaymentType
 * @property boolean $isReturn
 * @property boolean $batchable
 * @property Carbon $startTimeSlot
 * @property Carbon $endTimeSlot
 * @property float $deliveryFare
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property int|null $bikerId
 * @property string|null $bikerName
 * @property string|null $bikerPhoneNumber
 * @property string|null $bikerPhotoUrl
 * @property int|null $merchandiseStoreId
 * @property int|null $merchandiseCost
 * @property string|null $merchandiseDescription
 * @property string|null $invoiceStatus
 * @property Collection<int, DeliveryItem> $items
 * @property Collection<int, DeliveryTerminal> $terminals
 * @property Collection<int, StatusHistory> $statusHistories
 * @property Collection<int, InvoiceStatusHistory> $invoiceStatusHistories
 */
class Delivery extends Model
{
    protected $guarded = [];

    public const STATUS_PENDING = 'PENDING';

    public function items(): HasMany
    {
        return $this->hasMany(DeliveryItem::class);
    }

    public function terminals(): HasMany
    {
        return $this->hasMany(DeliveryTerminal::class);
    }

    public function statusHistories(): HasMany
    {
        return $this->hasMany(StatusHistory::class);
    }

    public function invoiceStatusHistories(): HasMany
    {
        return $this->hasMany(InvoiceStatusHistory::class);
    }

    protected static function booted(): void
    {
        static::created(function (Delivery $delivery) {
            StatusHistory::query()->create([
                'delivery_id' => $delivery->id,
                'status' => $delivery->status,
            ]);

            if ($delivery->invoiceStatus !== null) {
                InvoiceStatusHistory::query()->create([
                    'delivery_id' => $delivery->id,
                    'status' => $delivery->invoiceStatus,
                ]);
            }
        });

        static::updated(function (Delivery $delivery) {
            if ($delivery->isDirty('status')) {
                StatusHistory::query()->create([
                    'delivery_id' => $delivery->id,
                    'status' => $delivery->status,
                ]);

                DeliveryStatusUpdateWebhook::dispatch($delivery)->afterCommit();
            }

            if ($delivery->isDirty('invoiceStatus')) {
                InvoiceStatusHistory::query()->create([
                    'delivery_id' => $delivery->id,
                    'status' => $delivery->invoiceStatus,
                ]);

                InvoiceStatusUpdateWebhook::dispatch($delivery)->afterCommit();
            }
        });
    }
}
