<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('event_registrations')) {
            Schema::table('event_registrations', function (Blueprint $table) {
                if (!Schema::hasColumn('event_registrations', 'checked_in_at')) {
                    $table->timestamp('checked_in_at')->nullable()->after('notes');
                }
                if (!Schema::hasColumn('event_registrations', 'checked_in_by')) {
                    $table->unsignedInteger('checked_in_by')->nullable()->after('checked_in_at');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('event_registrations')) {
            Schema::table('event_registrations', function (Blueprint $table) {
                if (Schema::hasColumn('event_registrations', 'checked_in_by')) {
                    $table->dropColumn('checked_in_by');
                }
                if (Schema::hasColumn('event_registrations', 'checked_in_at')) {
                    $table->dropColumn('checked_in_at');
                }
            });
        }
    }
};
