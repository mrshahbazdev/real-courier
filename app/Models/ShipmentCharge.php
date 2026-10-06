<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ShipmentCharge extends Model
{
    protected $fillable = ['shipment_id', 'label', 'amount', 'sort_order'];

    protected $casts = ['amount' => 'decimal:2'];

    public function shipment(): BelongsTo
    {
        return $this->belongsTo(Shipment::class);
    }
}
