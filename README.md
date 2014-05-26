# Moderate for Laravel 4 - Alpha

A simple moderation system for Laravel 4.

----------

## Installation

- [Moderate on Packagist](https://packagist.org/packages/torann/moderate)
- [Moderate on GitHub](https://github.com/torann/laravel-moderate)

To get the latest version of Moderate simply require it in your `composer.json` file.

~~~
"torann/moderate": "dev-master"
~~~

You'll then need to run `composer install` to download it and have the autoloader updated.

Once Moderate is installed you need to register the service provider with the application. Open up `app/config/app.php` and find the `providers` key.

~~~php
'providers' => array(

    'Torann\Moderate\ModerateServiceProvider',

)
~~~

### Publish the config

Run this on the command line from the root of your project:

	$ php artisan config:publish torann/moderate

This will publish Moderate's config to ``app/config/packages/torann/moderate/``.

### Migration

Now migrate the database tables for Moderate. Run this on the command line from the root of your project:

	$ php artisan migrate --package=torann/moderate

### Example Model

Use the `HasModerations` trait in a existing model. For example:

~~~php
<?php

use Torann\Moderate\HasModerations;

class Comment extends Eloquent {

    use HasModerations;
    
    /**
     * The attributes on the model which are moderated.
     *
     * @var array
     */
    private $moderations = array(
        'title' => 'blacklist|links:2'
    );
...
~~~

The table will need to have a column called `moderated`, this is set to true or false during creation.

~~~php
$table->boolean('moderated')->default(false);
~~~

### Rules

- `blacklist` flags a string if it contains any of one or more words that has been added to the black list.

- `links` checks if a field contains too many links based on the max links allowed. The default can be set in the config file or in the rule as seen above.

### Black Lists

The black list is stored in a database. Which database table to used is specified in the config file under `blacklistTable`. Black list elements can be formed from Regular Expressions or a Character Sequence.

**Examples:**

- 10.0.2.2
- spammingsite.com
- [suck|ugly]
- [\d{3}\.\d{3}\.\d{3}\.\d{3}]


### Events

`blacklist.updated` must be fired when updating the black list table. This clears the cache. Add this to your black list model or controller.

`moderations.moderated` fired with an item is flagged. This can be used to send an email to the site admin.


## ToDos

- Add a whitelist for users. Not everyone's evil :-)

## Change Log

#### v0.1.0

- First release