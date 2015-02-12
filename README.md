# Moderate for Laravel 4 - Alpha

[![Latest Stable Version](https://poser.pugx.org/torann/moderate/v/stable.png)](https://packagist.org/packages/torann/moderate) [![Total Downloads](https://poser.pugx.org/torann/moderate/downloads.png)](https://packagist.org/packages/torann/moderate)

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

~~~
$ php artisan config:publish torann/moderate
~~~

This will publish Moderate's config to ``app/config/packages/torann/moderate/``.

### Migration

Now migrate the database tables for Moderate. Run this on the command line from the root of your project:

~~~
$ php artisan migrate --package=torann/moderate
~~~

## Documentation

[View the official documentation](http://lyften.com/projects/laravel-moderate/).

## ToDos

- Add a whitelist for users. Not everyone's evil :-)

## Change Log

#### v0.1.1

- Add Blacklist drivers
- Namespace cleanup
- Better caching
- Timestamp management

#### v0.1.0

- First release