<?php

namespace Workdo\AssistNow\Providers;

use Illuminate\Support\ServiceProvider;
use Workdo\AssistNow\Providers\EventServiceProvider;
use Workdo\AssistNow\Providers\RouteServiceProvider;

class AssistNowServiceProvider extends ServiceProvider
{

    protected $moduleName = 'AssistNow';
    protected $moduleNameLower = 'assistnow';

    public function register()
    {
        $this->app->register(RouteServiceProvider::class);
        $this->app->register(EventServiceProvider::class);
    }

    public function boot(\Illuminate\Routing\Router $router)
    {
        $this->loadRoutesFrom(__DIR__ . '/../Routes/web.php');
        $this->loadViewsFrom(__DIR__ . '/../Resources/views', 'assistnow');
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');
        $this->registerTranslations();

        // $router->aliasMiddleware('SetLocale', \Workdo\AssistNow\Http\Middleware\SetLocale::class);
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