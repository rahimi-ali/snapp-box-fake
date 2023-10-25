<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('webhook_subscriber_headers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('webhook_subscriber_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->text('value');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('webhook_subscriber_headers');
    }
};
