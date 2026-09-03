<?php

namespace Crm\WhatsApp\Providers;

use Illuminate\Support\ServiceProvider;
use Crm\WhatsApp\Console\Commands\ProcessScheduledCampaignsCommand;
use Crm\WhatsApp\Services\PhoneParserService;
use Crm\WhatsApp\Services\WhatsAppClientService;

class WhatsAppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');

        if ($this->app->runningInConsole()) {
            $this->commands([
                ProcessScheduledCampaignsCommand::class,
            ]);
        }
    }

    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../Config/whatsapp.php', 'whatsapp');

        $this->app->singleton(PhoneParserService::class, function ($app) {
            return new PhoneParserService(config('whatsapp.default_country_code', '91'));
        });

        $this->app->singleton(WhatsAppClientService::class, function ($app) {
            return new WhatsAppClientService(
                config('whatsapp.gateway_url', 'http://127.0.0.1:3001'),
                config('whatsapp.gateway_key')
            );
        });
    }
}
