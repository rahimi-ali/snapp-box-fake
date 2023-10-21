<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('delivery_terminals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('delivery_id')->constrained();
            $table->unsignedSmallInteger('sequence');
            $table->string('type');
            $table->string('paymentType');
            $table->string('status');
            $table->string('contactName');
            $table->string('contactPhoneNumber');
            $table->double('latitude');
            $table->double('longitude');
            $table->string('address');
            $table->string('comment')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('delivery_terminals');
    }
};
