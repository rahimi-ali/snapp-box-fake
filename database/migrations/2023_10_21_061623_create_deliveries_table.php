<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('deliveries', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customerId');
            $table->string('maskedId')->index();
            $table->string('status');
            $table->string('city');
            $table->string('customerRefId')->index();
            $table->string('customerName');
            $table->string('customerPhoneNumber')->nullable();
            $table->string('customerEmail')->nullable();
            $table->string('deliveryCategory');
            $table->string('deliveryFarePaymentType');
            $table->boolean('isReturn')->default(false);
            $table->boolean('batchable')->default(false);
            $table->dateTime('startTimeSlot');
            $table->dateTime('endTimeSlot');
            $table->double('deliveryFare');
            $table->unsignedBigInteger('bikerId')->nullable();
            $table->string('bikerName')->nullable();
            $table->string('bikerPhoneNumber')->nullable();
            $table->string('bikerPhotoUrl')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('deliveries');
    }
};
