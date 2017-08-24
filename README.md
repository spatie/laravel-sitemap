# Generate sitemaps with ease

[![Latest Version on Packagist](https://img.shields.io/packagist/v/spatie/laravel-sitemap.svg?style=flat-square)](https://packagist.org/packages/spatie/laravel-sitemap)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://img.shields.io/travis/spatie/laravel-sitemap/master.svg?style=flat-square)](https://travis-ci.org/spatie/laravel-sitemap)
[![Quality Score](https://img.shields.io/scrutinizer/g/spatie/laravel-sitemap.svg?style=flat-square)](https://scrutinizer-ci.com/g/spatie/laravel-sitemap)
[![StyleCI](https://styleci.io/repos/65549848/shield)](https://styleci.io/repos/65549848)
[![Total Downloads](https://img.shields.io/packagist/dt/spatie/laravel-sitemap.svg?style=flat-square)](https://packagist.org/packages/spatie/laravel-sitemap)

This package can generate a sitemap without you having to add urls to it manually. This works by crawling your entire site.

```php
use Spatie\Sitemap\SitemapGenerator;

SitemapGenerator::create('https://example.com')->writeToFile($path);
```

You can also create your sitemap manually:

```php
use Carbon\Carbon;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

Sitemap::create()

    ->add(Url::create('/home')
        ->setLastModificationDate(Carbon::yesterday())
        ->setChangeFrequency(Url::CHANGE_FREQUENCY_YEARLY)
        ->setPriority(0.1))

   ->add(...)

   ->writeToFile($path);
```

Or you can have the best of both worlds by generating a sitemap and then adding more links to it:

```php
SitemapGenerator::create('https://example.com')
   ->getSitemap()
   ->add(Url::create('/extra-page')
        ->setLastModificationDate(Carbon::yesterday())
        ->setChangeFrequency(Url::CHANGE_FREQUENCY_YEARLY)
        ->setPriority(0.1))

    ->add(...)

    ->writeToFile($path);
```

## Postcardware

You're free to use this package (it's [MIT-licensed](LICENSE.md)), but if it makes it to your production environment we highly appreciated you sending us postcard from your hometown, mentioning which of our package(s) you are using.

Our address is: Spatie, Samberstraat 69D, 2060 Antwerp, Belgium.

All postcards are published [on our website](https://spatie.be/en/opensource/postcards).

## Installation

First, install the package via composer:

``` bash
composer require spatie/laravel-sitemap
```

The package will automatically register itself.

If you want to update your sitemap automatically and frequently you need to perform [some extra steps](https://github.com/spatie/laravel-sitemap#generating-the-sitemap-frequently).


## Configuration

You can override the default options for the crawler. First publish the configuration:

```bash
php artisan vendor:publish --provider="Spatie\Sitemap\SitemapServiceProvider" --tag=config
```

This will copy the default config to `config/sitemap.php` where you can edit it.

```php
use GuzzleHttp\RequestOptions;

return [

    /*
     * These options will be passed to GuzzleHttp\Client when it is created.
     * For in-depth information on all options see the Guzzle docs:
     *
     * http://docs.guzzlephp.org/en/stable/request-options.html
     */
    'guzzle_options' => [

        /*
         * Whether or not cookies are used in a request.
         */
        RequestOptions::COOKIES => true,

        /*
         * The number of seconds to wait while trying to connect to a server.
         * Use 0 to wait indefinitely.
         */
        RequestOptions::CONNECT_TIMEOUT => 10,

        /*
         * The timeout of the request in seconds. Use 0 to wait indefinitely.
         */
        RequestOptions::TIMEOUT => 10,

        /*
         * Describes the redirect behavior of a request.
         */
        RequestOptions::ALLOW_REDIRECTS => false,
    ]

];
```

## Usage

### Generating a sitemap

The easiest way is to crawl the given domain and generate a sitemap with all found links.
The destination of the sitemap should be specified by `$path`.

```php
SitemapGenerator::create('https://example.com')->writeToFile($path);
```

The generated sitemap will look similiar to this:

```xml
<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <url>
        <loc>https://example.com</loc>
        <lastmod>2016-01-01T00:00:00+00:00</lastmod>
        <changefreq>daily</changefreq>
        <priority>0.8</priority>
    </url>
    <url>
        <loc>https://example.com/page</loc>
        <lastmod>2016-01-01T00:00:00+00:00</lastmod>
        <changefreq>daily</changefreq>
        <priority>0.8</priority>
    </url>

    ...
</urlset>
```

### Customizing the sitemap generator

#### Changing properties

To change the `lastmod`, `changefreq` and `priority` of the contact page:

```php
use Carbon\Carbon;
use Spatie\Sitemap\SitemapGenerator;
use Spatie\Sitemap\Tags\Url;

SitemapGenerator::create('https://example.com')
   ->hasCrawled(function (Url $url) {
       if ($url->segment(1) === 'contact') {
           $url->setPriority(0.9)
               ->setLastModificationDate(Carbon::create('2016', '1', '1'));
       }

       return $url;
   })
   ->writeToFile($sitemapPath);
```

#### Leaving out some links

If you don't want a crawled link to appear in the sitemap, just don't return it in the callable you pass to `hasCrawled `.

```php
use Spatie\Sitemap\SitemapGenerator;
use Spatie\Sitemap\Tags\Url;

SitemapGenerator::create('https://example.com')
   ->hasCrawled(function (Url $url) {
       if ($url->segment(1) === 'contact') {
           return;
       }

       return $url;
   })
   ->writeToFile($sitemapPath);
```

#### Preventing the crawler from crawling some pages
You can also instruct the underlying crawler to not crawl some pages by passing a `callable` to `shouldCrawl`.

```php
use Spatie\Sitemap\SitemapGenerator;
use Spatie\Crawler\Url;

SitemapGenerator::create('https://example.com')
   ->shouldCrawl(function (Url $url) {
       // All pages will be crawled, except the contact page.
       // Links present on the contact page won't be added to the
       // sitemap unless they are present on a crawlable page.
       return $url->segment(1) !== 'contact';
   })
   ->writeToFile($sitemapPath);
```

#### Manually adding links

You can manually add links to a sitemap:

```php
use Spatie\Sitemap\SitemapGenerator;
use Spatie\Sitemap\Tags\Url;

SitemapGenerator::create('https://example.com')
    ->getSitemap()
    // here we add one extra link, but you can add as many as you'd like
    ->add(Url::create('/extra-page')->setPriority(0.5))
    ->writeToFile($sitemapPath);
```

#### Adding alternates to links

Multilingual sites may have several alternate versions of the same page (one per language). Based on the previous example adding an alterante can be done as follows:

```php
use Spatie\Sitemap\SitemapGenerator;
use Spatie\Sitemap\Tags\Url;

SitemapGenerator::create('https://example.com')
    ->getSitemap()
    // here we add one extra link, but you can add as many as you'd like
    ->add(Url::create('/extra-page')->setPriority(0.5)->addAlternate('/extra-pagina', 'nl'))
    ->writeToFile($sitemapPath);
```

Note the ```addAlternate``` function which takes an alternate URL and the locale it belongs to.

### Manually creating a sitemap

You can also create a sitemap fully manual:

```php
use Carbon\Carbon;

Sitemap::create()
   ->add('/page1')
   ->add('/page2')
   ->add(Url::create('/page3')->setLastModificationDate(Carbon::create('2016', '1', '1')))
   ->writeToFile($sitemapPath);
```

### Creating a sitemap index
You can create a sitemap index:
```php
use Spatie\Sitemap\SitemapIndex;

SitemapIndex::create()
    ->add('/pages_sitemap.xml')
    ->add('/posts_sitemap.xml')
    ->writeToFile($sitemapIndexPath);
```

You can pass a `Spatie\Sitemap\Tags\Sitemap` object to manually set the `lastModificationDate` property.

```php
use Spatie\Sitemap\SitemapIndex;
use Spatie\Sitemap\Tags\Sitemap;

SitemapIndex::create()
    ->add('/pages_sitemap.xml')
    ->add(Sitemap::create('/posts_sitemap.xml')
        ->setLastModificationDate(Carbon::yesterday()))
    ->writeToFile($sitemapIndexPath);
```

the generated sitemap index will look similar to this:

```xml
<?xml version="1.0" encoding="UTF-8"?>
<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
   <sitemap>
      <loc>http://www.example.com/pages_sitemap.xml</loc>
      <lastmod>2016-01-01T00:00:00+00:00</lastmod>
   </sitemap>
   <sitemap>
      <loc>http://www.example.com/posts_sitemap.xml</loc>
      <lastmod>2015-12-31T00:00:00+00:00</lastmod>
   </sitemap>
</sitemapindex>
```

## Generating the sitemap frequently

Your site will probably be updated from time to time. In order to let your sitemap reflect these changes you can run the generator periodically. The easiest way of doing this is do make use of Laravel's default scheduling capabilities.

You could setup an artisan command much like this one:

```php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Sitemap\SitemapGenerator;

class GenerateSitemap extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'sitemap:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate the sitemap.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // modify this to your own needs
        SitemapGenerator::create(config('app.url'))
            ->writeToFile(public_path('sitemap.xml'));
    }
}
```

That command should then be scheduled in the console kernel.

```php
// app/Console/Kernel.php
protected function schedule(Schedule $schedule)
{
    ...
    $schedule->command('sitemap:generate')->daily();
    ...
}
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

First start the test server in a seperate terminal session:

``` bash
cd tests/server
./start_server.sh
```

With the server running you can execute the tests:

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
