
# Mylinex Administration
## Getting started

## Installation

Require the mylinex/administration package in your composer.json and update your dependencies:

    composer require mylinex/administration

## Configuration

The defaults are set in config/app.php. Publish the config to copy the file to your own config:

    php artisan vendor:publish --provider="Administration\AdministrationServiceProvider"

Please add this route to your web.php

    Route::get('/', function () {
        return view('Administration::auth.login');
    });

Add this line to DatabaseSeeder.php in /database/seeds/

    $this->call([
        PermissionsTableSeeder::class,
        MenuTableSeeder::class,
        RolesTableSeeder::class,
        UsersTableSeeder::class
    ]);

** If your application is with laravel 9 and PHP 8

Change app/Http/Middleware/TrustProxies.php

    protected $headers = Request::HEADER_X_FORWARDED_ALL;

    //to 

    protected $headers = 
        Request::HEADER_X_FORWARDED_FOR |
        Request::HEADER_X_FORWARDED_HOST |
        Request::HEADER_X_FORWARDED_PORT |
        Request::HEADER_X_FORWARDED_PROTO |
        Request::HEADER_X_FORWARDED_AWS_ELB;

Migrate database & seed

    php artisan migrate

    php artisan db:seed

Run development server

    php artisan serve


