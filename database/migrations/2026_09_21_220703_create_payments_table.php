<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->string('provider');
            $table->unsignedInteger('amount');
            $table->enum('status', ['pending', 'paid', 'failed', 'cancelled'])->default('pending');
            $table->string('provider_reference')->nullable();
            $table->string('checkout_request_id')->nullable()->unique();
            $table->json('raw_response')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
