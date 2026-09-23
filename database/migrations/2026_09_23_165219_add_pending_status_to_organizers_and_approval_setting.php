<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('organizers', function (Blueprint $table) {
            $table->enum('status', ['active', 'suspended', 'pending'])->default('active')->change();
        });

        Schema::table('settings', function (Blueprint $table) {
            $table->boolean('require_organizer_approval')->default(false)->after('payment_driver');
        });

        // Setting::current() caches the row forever; the cached instance from
        // before this column existed would otherwise keep returning null for
        // it until something else happens to save the settings row.
        Cache::forget('tiko.settings');
    }

    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn('require_organizer_approval');
        });

        Schema::table('organizers', function (Blueprint $table) {
            $table->enum('status', ['active', 'suspended'])->default('active')->change();
        });
    }
};
