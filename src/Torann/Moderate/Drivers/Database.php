<?php

namespace Torann\Moderate\Drivers;

use DB;

class Database extends AbstractDriver
{
    /**
     * {@inheritdoc}
     */
    public function getList()
    {
        // Start query
        $query = DB::table($this->getConfig('table', 'blacklists'));

        // Multiple locale support
        if ($this->getConfig('locales', false)) {
            $query->where('locale', $this->getLocal());
        }

        return $query->pluck('element');
    }
}
