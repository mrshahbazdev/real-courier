<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Shipment extends Model
{
    protected $fillable = [
        'tracking_no',
        'sender_name',
        'sender_address',
        'sender_delivery',
        'consignee_name',
        'consignee_phone',
        'consignee_address',
        'description',
        'delivery_location',
        'status',
        'shipment_date',
        'registration_fee',
        'invoice_template',
    ];

    protected $casts = [
        'shipment_date' => 'date',
        'registration_fee' => 'decimal:2',
    ];

    public function events(): HasMany
    {
        return $this->hasMany(ShipmentEvent::class)->orderByDesc('happened_at')->orderByDesc('id');
    }

    public function charges(): HasMany
    {
        return $this->hasMany(ShipmentCharge::class)->orderBy('sort_order')->orderBy('id');
    }

    public function totalCharges(): float
    {
        return (float) $this->charges->sum('amount');
    }

    public static function generateTrackingNo(): string
    {
        do {
            $no = 'SHP'.str_pad((string) random_int(0, 999), 3, '0', STR_PAD_LEFT)
                .str_pad((string) random_int(0, 9999), 4, '0', STR_PAD_LEFT)
                .str_pad((string) random_int(0, 9999), 4, '0', STR_PAD_LEFT);
        } while (static::where('tracking_no', $no)->exists());

        return $no;
    }

    public static function normalizeTrackingNo(string $value): string
    {
        return strtoupper(preg_replace('/[^A-Za-z0-9]/', '', $value));
    }

    public function formattedTrackingNo(): string
    {
        return trim(chunk_split($this->tracking_no, 3, ' '));
    }
}
