<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $fillable = ['key', 'value'];

    public const PAYMENT_METHODS = ['visa', 'paypal', 'mastercard', 'stripe', 'gpay', 'applepay'];

    public const INVOICE_TEMPLATES = [
        't1' => 'Classic Shipping Card',
        't2' => 'Minimal Receipt',
        't3' => 'Dark Freight',
        't4' => 'Formal Invoice',
        't5' => 'Compact Express',
    ];

    public const DEFAULTS = [
        'company_name' => 'Shipshares',
        'tagline' => 'Delivery Company',
        'head_office' => 'Canada, USA, UK, Asia & Europe',
        'email' => 'shipshareslogisticscompany@gmail.com',
        'phone' => '+1 (859) 329-8124',
        'address' => 'Head Office: Canada, USA, UK, Asia & Europe',
        'hero_title' => 'Worldwide Express Delivery & Freight Services',
        'hero_text' => 'Global courier & freight services — track your shipment in real time.',
        'hero_image' => 'img/hero.jpg',
        'about_title' => 'Trusted Logistics Partner Since 2005',
        'about_text' => 'Shipshares Delivery Company provides fast, secure and reliable courier services across the globe. From express parcels to full freight forwarding, our network connects senders and consignees on every continent.',
        'about_image' => 'img/about.jpg',
        'service1_title' => 'Express Courier',
        'service1_text' => 'Door-to-door express delivery with real-time tracking and signature on delivery.',
        'service2_title' => 'Air Freight',
        'service2_text' => 'Fast international air freight with customs clearance handled end-to-end.',
        'service3_title' => 'Cargo & Trucking',
        'service3_text' => 'Ground freight and container trucking across North America, Europe and Asia.',
        'stat1_num' => '120+',
        'stat1_label' => 'Countries Served',
        'stat2_num' => '1.2M',
        'stat2_label' => 'Parcels Delivered',
        'stat3_num' => '24/7',
        'stat3_label' => 'Customer Support',
        'footer_text' => 'Reliable courier, air freight and cargo services worldwide.',
        'payment_methods' => 'visa,paypal,mastercard,stripe,gpay,applepay',
        'status_options' => "Pending Pickup\nShipment Registered\nIn Transit\nArrived at Facility\nOut for Delivery\nOn Hold\nDelivered",
        'charge_options' => "Registration Fee\nIncome Tax\nDiamond Ring Registration Fee\nMoney Exchange Fee\nRolex Watch Fee\nSignature Charges\nCustoms Clearance\nInsurance Fee",
        'logo' => 'img/logo.png',
        'signature' => null,
        'authorized_signature_name' => '',
        'default_invoice_template' => 't1',
    ];

    public static function get(string $key, ?string $default = null): ?string
    {
        $value = Cache::rememberForever("setting:{$key}", function () use ($key) {
            return static::query()->where('key', $key)->value('value');
        });

        if ($value === null) {
            return $default ?? (static::DEFAULTS[$key] ?? null);
        }

        return $value;
    }

    public static function put(string $key, ?string $value): void
    {
        static::updateOrCreate(['key' => $key], ['value' => $value]);
        Cache::forget("setting:{$key}");
    }

    public static function allSettings(): array
    {
        $stored = static::query()->pluck('value', 'key')->all();

        return array_merge(static::DEFAULTS, $stored);
    }

    public static function enabledPaymentMethods(): array
    {
        $raw = static::get('payment_methods');

        return array_values(array_filter(array_map('trim', explode(',', (string) $raw))));
    }

    public static function lines(string $key): array
    {
        $raw = static::get($key, '');

        return array_values(array_filter(array_map('trim', preg_split('/\r?\n/', (string) $raw))));
    }
}
