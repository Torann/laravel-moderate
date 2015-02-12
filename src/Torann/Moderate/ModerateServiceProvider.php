<?php namespace Torann\Moderate;

use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\AliasLoader;

class ModerateServiceProvider extends ServiceProvider {

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->package('torann/moderate');

        // Add 'Moderate' facade alias
        AliasLoader::getInstance()->alias('Moderate', 'Torann\Moderate\Facades\Moderate');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerCache();
        $this->registerModerate();
    }

    /**
     * Register the collection repository.
     *
     * @return void
     */
    protected function registerCache()
    {
        $this->app['torann.moderate.cache'] = $this->app->share(function($app)
        {
            $meta = $app['config']->get('app.manifest');
            $config = $app->config->get('moderate::config', array());

            return new Cache($meta, $config);
        });
    }

    /**
     * Register the collection repository.
     *
     * @return void
     */
    protected function registerModerate()
    {
        $this->app->bind('torann.moderate', function ($app)
        {
            $config = $app->config->get('moderate::config', array());

            return new Moderator($config, $app['torann.moderate.cache']);
        });

        $app = $this->app;

        // Register update event with the application
        $this->app['events']->listen('blacklist.updated', function () use ($app)
        {
            $app['torann.moderate']->reloadBlacklist();
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return array();
    }

}