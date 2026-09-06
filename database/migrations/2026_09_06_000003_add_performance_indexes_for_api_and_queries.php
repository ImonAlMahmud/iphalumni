<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations to add high-performance database indexes.
     */
    public function up(): void
    {
        $this->addIndexSafe('students_reference', 'idx_sr_batch_roll', ['batch', 'roll']);
        $this->addIndexSafe('students_reference', 'idx_sr_session_dept', ['session', 'department']);
        $this->addIndexSafe('students_reference', 'idx_sr_roll', ['roll']);

        $this->addIndexSafe('alumni_profiles', 'idx_ap_status_deleted', ['status', 'deleted_at']);
        $this->addIndexSafe('alumni_profiles', 'idx_ap_batch_status', ['batch_year', 'status']);
        $this->addIndexSafe('alumni_profiles', 'idx_ap_phone', ['phone']);
        $this->addIndexSafe('alumni_profiles', 'idx_ap_created_at', ['created_at']);

        $this->addIndexSafe('membership_payments', 'idx_mp_status_txn', ['status', 'transaction_id']);
        $this->addIndexSafe('membership_payments', 'idx_mp_created_at', ['created_at']);
        $this->addIndexSafe('membership_payments', 'idx_mp_method', ['method']);

        $this->addIndexSafe('memberships', 'idx_mem_profile_status', ['alumni_profile_id', 'status']);

        $this->addIndexSafe('events', 'idx_events_status_date', ['status', 'event_date']);
        $this->addIndexSafe('news', 'idx_news_status_published', ['status', 'published_at']);
        $this->addIndexSafe('jobs', 'idx_jobs_status_vis', ['status', 'visibility']);
        $this->addIndexSafe('api_tokens', 'idx_api_tokens_token_expires', ['token', 'expires_at']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $this->dropIndexSafe('students_reference', 'idx_sr_batch_roll');
        $this->dropIndexSafe('students_reference', 'idx_sr_session_dept');
        $this->dropIndexSafe('students_reference', 'idx_sr_roll');

        $this->dropIndexSafe('alumni_profiles', 'idx_ap_status_deleted');
        $this->dropIndexSafe('alumni_profiles', 'idx_ap_batch_status');
        $this->dropIndexSafe('alumni_profiles', 'idx_ap_phone');
        $this->dropIndexSafe('alumni_profiles', 'idx_ap_created_at');

        $this->dropIndexSafe('membership_payments', 'idx_mp_status_txn');
        $this->dropIndexSafe('membership_payments', 'idx_mp_created_at');
        $this->dropIndexSafe('membership_payments', 'idx_mp_method');

        $this->dropIndexSafe('memberships', 'idx_mem_profile_status');

        $this->dropIndexSafe('events', 'idx_events_status_date');
        $this->dropIndexSafe('news', 'idx_news_status_published');
        $this->dropIndexSafe('jobs', 'idx_jobs_status_vis');
        $this->dropIndexSafe('api_tokens', 'idx_api_tokens_token_expires');
    }

    /**
     * Safely add an index if table exists and index does not yet exist.
     */
    private function addIndexSafe(string $tableName, string $indexName, array $columns): void
    {
        try {
            if (!Schema::hasTable($tableName)) return;

            $existing = DB::select("SHOW INDEX FROM `{$tableName}` WHERE Key_name = ?", [$indexName]);
            if (empty($existing)) {
                $colsEscaped = implode('`, `', $columns);
                DB::statement("ALTER TABLE `{$tableName}` ADD INDEX `{$indexName}` (`{$colsEscaped}`)");
            }
        } catch (\Throwable $e) {
            // Ignore if index already exists
        }
    }

    /**
     * Safely drop an index if table and index exist.
     */
    private function dropIndexSafe(string $tableName, string $indexName): void
    {
        try {
            if (!Schema::hasTable($tableName)) return;

            $existing = DB::select("SHOW INDEX FROM `{$tableName}` WHERE Key_name = ?", [$indexName]);
            if (!empty($existing)) {
                DB::statement("ALTER TABLE `{$tableName}` DROP INDEX `{$indexName}`");
            }
        } catch (\Throwable $e) {
            // Ignore if index not found
        }
    }
};
