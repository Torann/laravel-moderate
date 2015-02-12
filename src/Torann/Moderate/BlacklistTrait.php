<?php namespace Torann\Moderate;

use Event;

trait BlacklistTrait
{
    /**
     * Register eloquent event handlers for blacklist updates.
     *
     * @return void
     */
    public static function bootBlacklist()
    {
        static::saved(function ($model)
        {
            Event::fire('blacklist.updated');
        });

        static::deleted(function ($model)
        {
            Event::fire('blacklist.updated');
        });
    }
}
