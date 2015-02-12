<?php namespace Torann\Moderate\Drivers;

use DB;

class Database extends AbstractDriver {

    /**
     * @return array
     */
    public function getList()
    {
        $tableName = array_get($this->config, 'blacklistTable', 'blacklists');

        return DB::table($tableName)->lists('element');
    }
}
