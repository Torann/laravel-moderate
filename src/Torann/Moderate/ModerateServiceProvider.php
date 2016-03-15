<?php

namespace Torann\Moderate;

use Illuminate\Support\ServiceProvider;

class ModerateServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->isLumen() === false) {
            $this->publishes([
                __DIR__ . '/../../config/moderate.php' => config_path('moderate.php'),
            ]);

            $this->mergeConfigFrom(
                __DIR__ . '/../../config/moderate.php', 'moderate'
            );
        }
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(Moderator::class, function ($app) {
            $config = $app->config->get('moderate', []);

            return new Moderator($config, $app['cache'], $app->getLocale());
        });

        // Register update event with the application
        $this->app['events']->listen('blacklist.updated', function () {
            $this->app[Moderator::class]->reloadBlacklist();
        });
    }

    /**
     * Check if package is running under Lumen app
     *
     * @return bool
     */
    protected function isLumen()
    {
        return str_contains($this->app->version(), 'Lumen') === true;
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [];
    }
}