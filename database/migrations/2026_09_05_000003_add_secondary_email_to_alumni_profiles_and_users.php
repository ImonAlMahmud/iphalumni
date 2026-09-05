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
        if (Schema::hasTable('alumni_profiles') && !Schema::hasColumn('alumni_profiles', 'secondary_email')) {
            Schema::table('alumni_profiles', function (Blueprint $table) {
                $table->string('secondary_email')->nullable()->after('phone');
            });
        }

        if (Schema::hasTable('users') && !Schema::hasColumn('users', 'secondary_email')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('secondary_email')->nullable()->after('email');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('alumni_profiles') && Schema::hasColumn('alumni_profiles', 'secondary_email')) {
            Schema::table('alumni_profiles', function (Blueprint $table) {
                $table->dropColumn('secondary_email');
            });
        }

        if (Schema::hasTable('users') && Schema::hasColumn('users', 'secondary_email')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('secondary_email');
            });
        }
    }
};
