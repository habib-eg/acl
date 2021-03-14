<?php

namespace Habib\Acl;

use Habib\Acl\Router\AclRouter;
use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;

class AclServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot(): void
    {
        $path =dirname(__DIR__);
        // $this->loadTranslationsFrom($path.'/resources/lang', 'habib');
        // $this->loadViewsFrom($path.'/resources/views', 'habib');
         $this->loadMigrationsFrom($path.'/database/migrations');
//         $this->loadRoutesFrom(__DIR__.'/routes.php');

        // Publishing is only necessary when using the CLI.
        if ($this->app->runningInConsole()) {
            $this->bootForConsole();
        }
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register(): void
    {
        Router::mixin(new AclRouter);
        $this->mergeConfigFrom(__DIR__.'/../config/acl.php', 'acl');

        // Register the service the package provides.
        $this->app->singleton('acl', function ($app) {
            return new Acl;
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['acl'];
    }

    /**
     * Console-specific booting.
     *
     * @return void
     */
    protected function bootForConsole(): void
    {
        $path =dirname(__DIR__);
        // Publishing the configuration file.
        $this->publishes([
            $path.'/config/acl.php' => config_path('acl.php'),
        ], 'acl.config');

        // Publishing the views.
        $this->publishes([
            $path.'/resources/views' => resource_path('views/vendor/habib'),
        ], 'acl.views');

        // Publishing the views.
        $this->publishes([
            $path.'/database/migrations' => database_path('migrations'),
        ], 'acl.migrations');

        // Publishing assets.
        /*$this->publishes([
            $path.'/resources/assets' => public_path('vendor/habib'),
        ], 'acl.views');*/

        // Publishing the translation files.
        /*$this->publishes([
            $path.'/resources/lang' => resource_path('lang/vendor/habib'),
        ], 'acl.views');*/

        // Registering package commands.
        // $this->commands([]);

    }
}
