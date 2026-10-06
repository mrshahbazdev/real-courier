<?php

namespace Database\Seeders;

use App\Models\Shipment;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@shipshares.com'],
            ['name' => 'Admin', 'password' => Hash::make('password')]
        );

        $shipment = Shipment::updateOrCreate(
            ['tracking_no' => 'SHP48212398476'],
            [
                'sender_name' => 'Yuna',
                'sender_address' => 'Netherlands',
                'sender_delivery' => 'Paraguay',
                'consignee_name' => 'Ilsen Cabrera',
                'consignee_phone' => '+595 982 374 962',
                'consignee_address' => 'Ferrea 1453, Asunción, Paraguay',
                'description' => 'Undisclosed Box',
                'delivery_location' => 'Paraguay',
                'status' => 'In Transit',
                'shipment_date' => '2026-04-13',
            ]
        );

        if ($shipment->charges()->count() === 0) {
            $shipment->charges()->createMany([
                ['label' => 'Registration Fee', 'amount' => 5000, 'sort_order' => 1],
                ['label' => 'Income Tax', 'amount' => 0, 'sort_order' => 2],
                ['label' => 'Diamond Ring Registration Fee', 'amount' => 0, 'sort_order' => 3],
                ['label' => 'Money Exchange Fee', 'amount' => 0, 'sort_order' => 4],
                ['label' => 'Rolex Watch Fee', 'amount' => 0, 'sort_order' => 5],
                ['label' => 'Signature Charges', 'amount' => 0, 'sort_order' => 6],
            ]);
        }

        if ($shipment->events()->count() === 0) {
            $shipment->events()->createMany([
                ['status' => 'Shipment registered', 'location' => 'Netherlands', 'description' => 'Package registered at origin facility', 'happened_at' => '2026-04-13 09:15'],
                ['status' => 'Departed origin facility', 'location' => 'Amsterdam, Netherlands', 'description' => 'Departed sorting hub', 'happened_at' => '2026-04-14 18:40'],
                ['status' => 'In Transit', 'location' => 'En route to Paraguay', 'description' => 'Shipment in transit to destination country', 'happened_at' => '2026-04-16 07:05'],
            ]);
        }
    }
}
