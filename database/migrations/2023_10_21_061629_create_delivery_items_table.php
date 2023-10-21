<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('delivery_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('delivery_id')->constrained();
            $table->unsignedSmallInteger('dropOffSequenceNumber');
            $table->unsignedSmallInteger('pickedUpSequenceNumber');
            $table->string('name');
            $table->unsignedBigInteger('quantity');
            $table->string('quantityMeasuringUnit');
            $table->unsignedBigInteger('packageValue')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('delivery_items');
    }
};
