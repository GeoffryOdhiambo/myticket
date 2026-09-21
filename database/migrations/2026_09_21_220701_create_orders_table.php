<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->foreignId('event_id')->constrained()->restrictOnDelete();
            $table->string('customer_name');
            $table->string('customer_whatsapp');
            $table->string('customer_email');
            $table->unsignedInteger('subtotal');
            $table->unsignedInteger('total');
            $table->enum('status', ['pending', 'paid', 'failed', 'cancelled'])->default('pending');
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();

            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
