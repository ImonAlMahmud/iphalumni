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
    }
}
