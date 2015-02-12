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
        $this->registerDriver();
        $this->registerModerate();
    }

    /**
     * Register the blacklist driver.
     *
     * @return void
     */
    protected function registerDriver()
    {
        $this->app['torann.moderate.driver'] = $this->app->share(function($app)
        {
            $config = $app->config->get('moderate::config', array());

            return new $config['driver']($app, $config);
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
            // Read settings from config file
            $config = $app->config->get('moderate::config', array());

            // Get Black list items
            $blackList = $app['torann.moderate.driver']->getList();

            // Create instance
            return new Moderator($config, $blackList);
        });

        $app = $this->app;

        // Register update event with the application
        $this->app['events']->listen('blacklist.updated', function () use ($app)
        {
            $app['torann.moderate.driver']->flushCache();
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