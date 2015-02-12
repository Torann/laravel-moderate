<?php namespace Torann\Moderate\Drivers;

use \Illuminate\Foundation\Application;

abstract class AbstractDriver {

    /**
     * Application instance
     *
     * @var \Illuminate\Foundation\Application
     */
    protected $app;

    /**
     * Moderate config
     *
     * @var array
     */
    protected $config;

    /**
     * Table name
     *
     * @var string
     */
    protected $tableName;

    /**
     * Constructor
     *
     * @param Application $app
     * @param array       $config
     */
    public function __construct(Application $app, array $config = array())
    {
        $this->app       = $app;
        $this->config    = $config;
        $this->tableName = array_get($config, 'blacklistTable', 'blacklists');
    }

    /**
     * Get list of blacklisted items
     *
     * @return array
     */
    abstract public function getList();

    /**
     * Clean blacklist from cache
     *
     * @return array
     */
    abstract public function flushCache();
}