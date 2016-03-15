<?php

namespace Torann\Moderate;

trait BlacklistTrait
{
    /**
     * Register eloquent event handlers for blacklist updates.
     *
     * @return void
     */
    public static function bootBlacklistTrait()
    {
        static::saved(function ($model) {
            event('blacklist.updated');
        });

        static::deleted(function ($model) {
            event('blacklist.updated');
        });
    }
}
