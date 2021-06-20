ReGenerator for Laravel Backpack
===
**A CRUD interface for the ReGenerator report generator built using [Laravel Backpack](https://github.com/Laravel-Backpack).**

This is meant to complement ReGenerator's features and not replace them. 
Use this interface sparingly, as it gives direct write access to your database. Never allow your end users to create or update reports!

Should work on any version of Laravel above 5.6, but was only tested on v8.

- [ReGenerator for Laravel Backpack](#regenerator-for-laravel-backpack)
	- [Screenshots](#screenshots)
	- [Installation](#installation)
		- [Optional](#optional)
	- [Usage](#usage)
	- [Setting permissions](#setting-permissions)
	- [Overwriting](#overwriting)
		- [Changinng the default URL route](#changinng-the-default-url-route)
		- [Changing the report preview button and / or HTML modal](#changing-the-report-preview-button-and--or-html-modal)
	- [Errors & Suggestions](#errors--suggestions)
	- [License](#license)

## Screenshots
![screenshot1](https://user-images.githubusercontent.com/17587578/122626018-bc7c0d00-d0b0-11eb-933a-ded154ac07f0.png)

![screenshot2](https://user-images.githubusercontent.com/17587578/122625981-99e9f400-d0b0-11eb-85ac-b78477801c18.png)

## Installation
Require the package via Composer:
```bash
composer require md0/backpack-reportgenerator
```
Run the database migrations if you haven't already done so when installing ReGenerator:
```bash   
php artisan migrate
```
Publish ReGenerator's assets to enable the chart output. This will place Chart.js in your public folder:
```bash
php artisan vendor:publish --provider="MD0\ReGenerator\ReGeneratorServiceProvider" --tag="assets"
```
### Optional
Add a menu entry for the new *Reports* page inside *resources/views/vendor/backpack/base/inc/sidebar_content.blade.php*:
```html
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('reports') }}"><i class="nav-icon la la-file-invoice"></i>{{ __('Reports') }}</a></li>
```
Publish the language files, if  you need to translate the intarface. You'll find the JSON files inside the *resources/lang/vendor/backpack-regenerator* folder. Feel free to contribute additional tanslations if you find this package useful.
```bash
provider="MD0\BackpackReGenerator\BackpackReGeneratorServiceProvider" --tag="lang"
```
## Usage
Point your browser to *[backpack_url]/reports* and manage your reports using Backpack CRUD controls.
## Setting permissions
To limit user access to certain CRUD operations and / or certain report groups you may set the persmissions dynamically by overwriting the config values in a middleware (this example assumes you're using the [Laravel Permission](https://github.com/spatie/laravel-permission) package):
```php
if (auth()->user()->hasRole('accounting')) {
	config()->set('md0.backpack-regenerator.allow_update', false);
	config()->set('md0.backpack-regenerator.restrict_by_tag', 'accounting');
}
```
## Overwriting
### Changinng the default URL route
To change the default */reports* URL, you'll have to:
1. Publish the routes file and change the route path:
```bash
php artisan vendor:publish --provider="MD0\BackpackReGenerator\BackpackReGeneratorServiceProvider" --tag="routes"
```
Replace all occurences of *reports* inside *routes/backpack-regenerator.php* with your chosen alternative.

2. Publish the config file and update Backpack's route:
```bash
php artisan vendor:publish --provider="MD0\BackpackReGenerator\BackpackReGeneratorServiceProvider" --tag="config"
```
Replace the value for *route_name* inside config/md0/backpack-regenerator.php.
### Changing the report preview button and / or HTML modal
Publish the views and edit the files inside *resources/views/vendor/md0/backpack-regenerator*. The view files ar stored in folders following the Backpack structure (buttons, columns).
```bash
php artisan vendor:publish --provider="MD0\BackpackReGenerator\BackpackReGeneratorServiceProvider" --tag="views"
```
## Errors & Suggestions
Please submit your improvement suggestions or report bugs / errors in the `Issues` section.
## License
Distributed under the GPL-3.0 License. See `LICENSE` for more information.
