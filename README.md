# Create and generate sitemaps with ease [WIP]

[![Latest Version on Packagist](https://img.shields.io/packagist/v/spatie/laravel-sitemap.svg?style=flat-square)](https://packagist.org/packages/spatie/laravel-sitemap)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://img.shields.io/travis/spatie/laravel-sitemap/master.svg?style=flat-square)](https://travis-ci.org/spatie/laravel-sitemap)
[![SensioLabsInsight](https://img.shields.io/sensiolabs/i/8b8a2545-76b3-4f24-bf35-64e49adfa2cf.svg?style=flat-square)](https://insight.sensiolabs.com/projects/8b8a2545-76b3-4f24-bf35-64e49adfa2cf)
[![Quality Score](https://img.shields.io/scrutinizer/g/spatie/laravel-sitemap.svg?style=flat-square)](https://scrutinizer-ci.com/g/spatie/laravel-sitemap)
[![StyleCI](https://styleci.io/repos/65549848/shield)](https://styleci.io/repos/65549848)
[![Total Downloads](https://img.shields.io/packagist/dt/spatie/laravel-sitemap.svg?style=flat-square)](https://packagist.org/packages/spatie/laravel-sitemap)

This package makes it easy to create a sitemap manually:

```php
use Spatie\Sitemap\Sitemap;
use Spatie\Tags\Url;

Sitemap::create()

    ->add(Url::create('/home')
        ->lastModificationDate($this->now->subDay())
        ->changeFrequency(Url::CHANGE_FREQUENCY_YEARLY)
        ->priority(0.1)
        
   ->add(...)
   
   ->writeToFile($path);
```

It can also generate a sitemap without you having to add urls to it manually. This works by just crawling your entire site.

```php
use Spatie\Sitemap\Sitemap\SitemapGenerator;

//magic
SitemapGenerator::create('https://spatie')->writeToFile($path);
```

Spatie is a webdesign agency based in Antwerp, Belgium. You'll find an overview of all our open source projects [on our website](https://spatie.be/opensource).

## Postcardware

You're free to use this package (it's [MIT-licensed](LICENSE.md)), but if it makes it to your production environment you are required to send us a postcard from your hometown, mentioning which of our package(s) you are using.

Our address is: Spatie, Samberstraat 69D, 2060 Antwerp, Belgium.

The best postcards will get published on the open source page on our website.

## Installation

You can install the package via composer:

``` bash
composer require spatie/laravel-sitemap
```

You must install the service provider

```php
// config/app.php
'providers' => [
    ...
    Spatie\Sitemap\Sitemap::class,
];
```

## Usage

### Creating a sitemap



## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

First start the test server in a seperate terminal session:

``` bash
cd  tests/server
./start_server.sh
```

With the server running you can execute the tests
``` bash
$ composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security

If you discover any security related issues, please email freek@spatie.be instead of using the issue tracker.

## Credits

- [Freek Van der Herten](https://github.com/freekmurze)
- [All Contributors](../../contributors)

## About Spatie
Spatie is a webdesign agency based in Antwerp, Belgium. You'll find an overview of all our open source projects [on our website](https://spatie.be/opensource).

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
