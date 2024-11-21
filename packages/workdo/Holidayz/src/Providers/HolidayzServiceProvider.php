<?php

namespace Workdo\Holidayz\Providers;

use Illuminate\Support\ServiceProvider;
use Workdo\Holidayz\Providers\EventServiceProvider;
use Workdo\Holidayz\Providers\RouteServiceProvider;

class HolidayzServiceProvider extends ServiceProvider
{

    protected $moduleName = 'Holidayz';
    protected $moduleNameLower = 'holidayz';

    public function register()
    {
        $this->app->register(RouteServiceProvider::class);
        $this->app->register(EventServiceProvider::class);
    }

    public function boot(\Illuminate\Routing\Router $router)
    {
        $this->loadRoutesFrom(__DIR__ . '/../Routes/web.php');
        $this->loadViewsFrom(__DIR__ . '/../Resources/views', 'holidayz');
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');
        $this->registerTranslations();

        $router->aliasMiddleware('SetLocale', \Workdo\Holidayz\Http\Middleware\SetLocale::class);
    }

    /**
     * Register translations.
     *
     * @return void
     */
    public function registerTranslations()
    {
        $langPath = resource_path('lang/modules/' . $this->moduleNameLower);

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, $this->moduleNameLower);
            $this->loadJsonTranslationsFrom($langPath);
        } else {
            $this->loadTranslationsFrom(__DIR__.'/../Resources/lang', $this->moduleNameLower);
            $this->loadJsonTranslationsFrom(__DIR__.'/../Resources/lang');
        }
    }
}