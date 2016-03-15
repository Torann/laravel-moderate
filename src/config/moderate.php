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
     | Default Driver
     |--------------------------------------------------------------------------
     |
     | This option controls the default "driver" that will be used for the
     | blacklist. By default, we will use the lightweight native driver but
     | you may specify any of the other wonderful drivers provided here.
     |
     | Supported: \Torann\Moderate\Drivers\Local::class
     |
     */

    'driver' => \Torann\Moderate\Drivers\Local::class,

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
            'path'           => base_path('blacklist.json'),
            'ignore_missing' => true,
            'locales'        => false,
        ],

        'database' => [
            'table'   => 'blacklists',
            'locales' => false,
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

        'enabled' => true,

        'key' => 'moderate.blacklist',
    ],
];
