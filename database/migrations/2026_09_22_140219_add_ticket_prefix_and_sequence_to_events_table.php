<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->string('ticket_prefix', 4)->unique()->nullable()->after('slug');
            $table->unsignedInteger('ticket_sequence')->default(0)->after('ticket_prefix');
        });
    }

    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn(['ticket_prefix', 'ticket_sequence']);
        });
    }
};
