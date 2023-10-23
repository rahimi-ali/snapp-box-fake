<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $delivery_id
 * @property string $status
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class InvoiceStatusHistory extends Model
{
    protected $guarded = [];

    public function delivery(): BelongsTo
    {
        return $this->belongsTo(Delivery::class);
    }
}
