
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

Please added to your composer.json

    "mylinex/administration": '@dev',

    "scripts": {
        "post-autoload-dump": [
            "Illuminate\\Foundation\\ComposerScripts::postAutoloadDump",
            "@php artisan package:discover --ansi",
            "@php artisan vendor:publish --provider=Administration\\AdministrationServiceProvider --force"
        ]
    },
----------
