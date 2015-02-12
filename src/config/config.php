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
     | Default Driver
     |--------------------------------------------------------------------------
     |
     | This option controls the default "driver" that will be used for the
     | blacklist. By default, we will use the lightweight native driver but
     | you may specify any of the other wonderful drivers provided here.
     |
     | Note: Laravel Registry package is supported too ;-)
     |
     | Supported: "\\Torann\\Moderate\\Drivers\\Database",
     |            "\\Torann\\Moderate\\Drivers\\LaravelRegistry"
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
     | Default maximum of links
     |--------------------------------------------------------------------------
     |
     | The default maximum number of links allowed in a moderated item.
     |
     */

    'defaultMaxLinks' => 10,

);
