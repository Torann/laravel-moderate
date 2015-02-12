<?php

return array(

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
     | Supported: "\\Torann\\Moderate\\Drivers\\Database"
     |
     */

    'driver' => '\\Torann\\Moderate\\Drivers\\Database',

    /*
     |--------------------------------------------------------------------------
     | Table name for blacklisted items
     |--------------------------------------------------------------------------
     |
     | When using the "database" and "registry" driver, you may specify the
     | table we should use to manage the blacklisted items. Of course, a
     | sensible default is provided for you; however, you are free to
     | change this as needed.
     |
     */

    'blacklistTable' => 'blacklists',

    /*
     |--------------------------------------------------------------------------
     | Blacklist caching
     |--------------------------------------------------------------------------
     |
     | Helps speed up the the moderation process by caching the list.
     |
     */

    'cacheBlacklist' => true,

     /*
     |--------------------------------------------------------------------------
     | Cache timestamp
     |--------------------------------------------------------------------------
     |
     | Used for multi-instance web servers. This can be used to ensure
     | the registry for all instances are kept up to date.
     |
     | For Redis: \\Torann\\Moderate\\Timestamps\\Redis
     |
     */

    'timestamp_manager' => '',
);
