<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('organizers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('business_name');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('phone');
            $table->enum('status', ['active', 'suspended'])->default('active');
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('organizer_password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('organizer_password_reset_tokens');
        Schema::dropIfExists('organizers');
    }
};
