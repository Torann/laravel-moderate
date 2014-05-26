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
        AliasLoader::getInstance()->alias('Moderate', 'Torann\Moderate\Facade');
	}

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('torann.moderate', function ($app)
        {
            // Read settings from config file
            $config = $app->config->get('moderate::config', array());

            // Get Black list items
            $blackList = $app['cache']->rememberForever('torann.moderate.blacklist', function() use ($app, $config)
            {
                return $app['db']->table($config['blacklistTable'])->lists('element');
            });

            // Create instance
            return new Moderator($config, $blackList);
        });

        $app = $this->app;

        $this->app['events']->listen('blacklist.updated', function () use ($app)
        {
            $app['cache']->forget('torann.moderate.blacklist');
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