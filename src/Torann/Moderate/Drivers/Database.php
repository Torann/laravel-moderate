<?php namespace Torann\Moderate\Drivers;

class Database extends AbstractDriver {

    /**
     * @var string
     */
    protected $cacheName = 'torann.moderate.blacklist';

    /**
     * @return array
     */
    public function getList()
    {
        $app = $this->app;
        $tableName = $this->tableName;

        return $this->app['cache']->rememberForever($this->cacheName, function() use ($app, $tableName)
        {
            return $app['db']->table($tableName)->lists('element');
        });
    }

    /**
     * @return array
     */
    public function flushCache()
    {
        return $this->app['cache']->forget($this->cacheName);
    }
}
