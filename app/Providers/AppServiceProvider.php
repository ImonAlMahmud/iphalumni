<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->app->extend('translator', function ($translator, $app) {
            $trans = new \App\Services\CustomTranslator($app['translation.loader'], $app->getLocale());
            $trans->setFallback($app->getFallbackLocale());

            return $trans;
        });

        // Ensure secondary_email column exists on live production databases
        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('alumni_profiles') && !\Illuminate\Support\Facades\Schema::hasColumn('alumni_profiles', 'secondary_email')) {
                \Illuminate\Support\Facades\Schema::table('alumni_profiles', function ($table) {
                    $table->string('secondary_email')->nullable()->after('phone');
                });
            }
            if (\Illuminate\Support\Facades\Schema::hasTable('users') && !\Illuminate\Support\Facades\Schema::hasColumn('users', 'secondary_email')) {
                \Illuminate\Support\Facades\Schema::table('users', function ($table) {
                    $table->string('secondary_email')->nullable()->after('email');
                });
            }

            // Ensure all existing memberships follow IPHAA-0000X format
            if (\Illuminate\Support\Facades\Schema::hasTable('memberships')) {
                \Illuminate\Support\Facades\DB::table('memberships')
                    ->where(function ($q) {
                        $q->whereNull('membership_number')
                          ->orWhere('membership_number', 'not like', 'IPHAA-%');
                    })
                    ->update([
                        'membership_number' => \Illuminate\Support\Facades\DB::raw("CONCAT('IPHAA-', LPAD(COALESCE(alumni_profile_id, id), 5, '0'))")
                    ]);
            }

            // Clean up any duplicate alumni_education records
            if (\Illuminate\Support\Facades\Schema::hasTable('alumni_education')) {
                \Illuminate\Support\Facades\DB::statement("
                    DELETE e1 FROM alumni_education e1
                    INNER JOIN alumni_education e2 
                    WHERE e1.alumni_profile_id = e2.alumni_profile_id 
                      AND LOWER(TRIM(e1.degree)) = LOWER(TRIM(e2.degree))
                      AND (
                          ((e1.graduation_year IS NULL OR e1.graduation_year = '') AND (e2.graduation_year IS NOT NULL AND e2.graduation_year != ''))
                          OR (e1.id > e2.id AND COALESCE(e1.graduation_year, '') = COALESCE(e2.graduation_year, ''))
                      )
                ");
            }
        } catch (\Throwable $e) {
            // Fail silently if DB connection is not established during initial setup
        }
    }
}
