<?php namespace Torann\Moderate\Drivers;

use Registry;

class LaravelRegistry extends AbstractDriver {

    /**
     * @return array
     */
    public function getList()
    {
        return Registry::get($this->tableName, array());
    }

    /**
     * This is managed by Laravel Registry
     * @return array
     */
    public function flushCache()
    {
        return true;
    }
}
