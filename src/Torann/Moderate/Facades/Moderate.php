<?php namespace Torann\Moderate\Facades;

use Illuminate\Support\Facades\Facade;

class Moderate extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'torann.moderate';
    }
}
