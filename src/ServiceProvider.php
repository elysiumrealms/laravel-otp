<?php

namespace Elysiumrealms\Otp;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->offerPublishing();

        $this->registerCommands();

        $this->configureRateLimiting();

        $this->registerValidationRules();

        $this->loadRoutesFrom(__DIR__ . '/../routes/api.php');

        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'otp');

        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'otp');
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/otp.php',
            'otp'
        );

        $this->app->singleton(OtpService::class, function ($app) {
            return new OtpService($app);
        });
    }

    /**
     * Offer publishing
     *
     * @return void
     */
    protected function offerPublishing()
    {
        if (!$this->app->runningInConsole())
            return;

        $this->publishes([
            __DIR__ . '/../config/otp.php'
            => config_path('otp.php'),
        ], 'otp-config');

        $this->publishes([
            __DIR__ . '/../database/migrations'
            => database_path('migrations'),
        ], 'otp-migrations');

        $this->publishes([
            __DIR__ . '/../resources/lang'
            => resource_path('lang/vendor/otp'),
        ], 'otp-lang');

        $this->publishes([
            __DIR__ . '/../resources/assets/images'
            => public_path('vendor/laravel-otp/images'),
        ], 'otp-assets');
    }

    /**
     * Register commands
     *
     * @return void
     */
    protected function registerCommands()
    {
        if (!$this->app->runningInConsole())
            return;

        $this->commands([
            Console\Commands\CleanOtps::class,
            Console\Commands\ValidateOtp::class,
        ]);
    }

    /**
     * Register validation rules
     *
     * @return void
     */
    protected function registerValidationRules()
    {
        Validator::extend(
            'otp_verified',
            function ($attribute, $value, $parameters, $validator) {
                return $this->app[OtpService::class]->verified($value);
            }
        );
    }
    /**
     * Configure rate limiting
     *
     * @return void
     */
    protected function configureRateLimiting()
    {
        RateLimiter::for('otp', function (Request $request) {
            return Limit::perMinute(
                config('otp.routes.throttle')
            )->by($request->url() . $request->input('identifier'));
        });
    }
}
