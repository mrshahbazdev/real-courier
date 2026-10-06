<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shipments', function (Blueprint $table) {
            $table->id();
            $table->string('tracking_no')->unique();
            $table->string('sender_name')->nullable();
            $table->string('sender_address')->nullable();
            $table->string('sender_delivery')->nullable();
            $table->string('consignee_name')->nullable();
            $table->string('consignee_phone')->nullable();
            $table->string('consignee_address')->nullable();
            $table->string('description')->nullable();
            $table->string('delivery_location')->nullable();
            $table->string('status')->default('In Transit');
            $table->date('shipment_date')->nullable();
            $table->decimal('registration_fee', 12, 2)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shipments');
    }
};
