<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Convert to UTF8
    |--------------------------------------------------------------------------
    |
    | Used to normalize the string when checking blacklisted items in the
    | string.
    |
    */

    'asciiConversion' => true,

    /*
    |--------------------------------------------------------------------------
    | Default maximum of links
    |--------------------------------------------------------------------------
    |
    | The default maximum number of links allowed in a moderated item.
    |
    */

    'defaultMaxLinks' => 10,

    /*
    |--------------------------------------------------------------------------
    | Support Multiple Locales
    |--------------------------------------------------------------------------
    |
    | This option allows for multiple locale support in drivers, that is if
    | the driver supports it.
    |
    */

    'support_locales' => false,

    /*
    |--------------------------------------------------------------------------
    | Default Driver
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default moderation disk that should be used
    | by the framework.
    |
    | Supported: "local", "database"
    |
    */

    'driver' => 'local',

    /*
    |--------------------------------------------------------------------------
    | Driver Specific Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure as many drivers as you wish. The base class name
    | of the driver is used as the driver key.
    |
    */

    'drivers' => [

        'local' => [
            'class'          => \Torann\Moderate\Drivers\Local::class,
            'path'           => base_path('blacklist.json'),
            'ignore_missing' => true,
        ],

        'database' => [
            'class' => \Torann\Moderate\Drivers\Database::class,
            'table' => 'blacklists',
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Blacklist caching
    |--------------------------------------------------------------------------
    |
    | Helps speed up the the moderation process by caching the list.
    |
    */

    'cache' => [

        'enabled' => false,

        'key' => 'moderate.blacklist',
    ],
];
