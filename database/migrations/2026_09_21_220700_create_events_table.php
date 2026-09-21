<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organizer_id')->constrained()->cascadeOnDelete();
            $table->foreignId('category_id')->constrained('event_categories')->restrictOnDelete();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description');
            $table->string('image_url');
            $table->string('venue');
            $table->string('location');
            $table->date('event_date');
            $table->time('start_time');
            $table->time('end_time');
            $table->string('contact_email')->nullable();
            $table->string('contact_phone')->nullable();
            $table->enum('status', ['draft', 'published', 'unpublished', 'suspended'])->default('draft');
            $table->timestamps();

            $table->index(['status', 'event_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
