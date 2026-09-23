<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            // Filtered on every scanner check-in and every organizer/admin
            // ticket-list check-in filter.
            $table->index('checked_in');
        });

        Schema::table('orders', function (Blueprint $table) {
            // event_id + status is the filter shape used by every
            // tickets-sold/revenue aggregate across organizer and admin
            // dashboards. The existing standalone status index still
            // serves plain status lookups (as a prefix of this one).
            $table->index(['event_id', 'status']);
        });

        Schema::table('organizers', function (Blueprint $table) {
            // Event::published()'s homepage stats query filters active
            // organizers on every homepage load.
            $table->index('status');
        });

        Schema::table('ticket_types', function (Blueprint $table) {
            // Filtered on every event detail page and every checkout.
            $table->index(['event_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::table('ticket_types', function (Blueprint $table) {
            $table->dropIndex(['event_id', 'status']);
        });

        Schema::table('organizers', function (Blueprint $table) {
            $table->dropIndex(['status']);
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->dropIndex(['event_id', 'status']);
        });

        Schema::table('tickets', function (Blueprint $table) {
            $table->dropIndex(['checked_in']);
        });
    }
};
