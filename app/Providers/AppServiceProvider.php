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
        } catch (\Throwable $e) {
            // Fail silently if DB connection is not established during initial setup
        }
    }
}
