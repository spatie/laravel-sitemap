# Generate sitemaps with ease

[![Latest Version on Packagist](https://img.shields.io/packagist/v/spatie/laravel-sitemap.svg?style=flat-square)](https://packagist.org/packages/spatie/laravel-sitemap)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Test Status](https://img.shields.io/github/actions/workflow/status/spatie/laravel-sitemap/run-tests.yml?label=tests)](https://github.com/spatie/laravel-sitemap/actions/workflows/run-tests.yml)
[![Code Style Status](https://img.shields.io/github/actions/workflow/status/spatie/laravel-sitemap/php-cs-fixer.yml?label=code%20style)](https://github.com/spatie/laravel-sitemap/actions/workflows/php-cs-fixer.yml)
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
        ->setLastModificationDate(Carbon::yesterday()))
    ->add(...)
    ->writeToFile($path);
```

Or you can have the best of both worlds by generating a sitemap and then adding more links to it:

```php
SitemapGenerator::create('https://example.com')
    ->getSitemap()
    ->add(Url::create('/extra-page')
        ->setLastModificationDate(Carbon::yesterday()))
    ->add(...)
    ->writeToFile($path);
```

You can also control the maximum depth of the sitemap:
```php
SitemapGenerator::create('https://example.com')
    ->configureCrawler(function (Crawler $crawler) {
        $crawler->setMaximumDepth(3);
    })
    ->writeToFile($path);
```

The generator has [the ability to execute JavaScript](https://github.com/spatie/laravel-sitemap#executing-javascript) on each page so links injected into the dom by JavaScript will be crawled as well.

You can also use one of your available filesystem disks to write the sitemap to.
```php
SitemapGenerator::create('https://example.com')->getSitemap()->writeToDisk('public', 'sitemap.xml');
```

You may need to set the file visibility on one of your sitemaps. For example, if you are writing a sitemap to S3 that you want to be publicly available. You can set the third parameter to `true` to make it public. Note: This can only be used on the `->writeToDisk()` method.
```php
SitemapGenerator::create('https://example.com')->getSitemap()->writeToDisk('public', 'sitemap.xml', true);
```

You can also add your models directly by implementing the `\Spatie\Sitemap\Contracts\Sitemapable` interface.

```php
use Spatie\Sitemap\Contracts\Sitemapable;
use Spatie\Sitemap\Tags\Url;

class Post extends Model implements Sitemapable
{
    public function toSitemapTag(): Url | string | array
    {
        // Simple return:
        return route('blog.post.show', $this);

        // Return with fine-grained control:
        return Url::create(route('blog.post.show', $this))
            ->setLastModificationDate(Carbon::create($this->updated_at));
    }
}
```

Now you can add a single post model to the sitemap or even a whole collection.
```php
use Spatie\Sitemap\Sitemap;

Sitemap::create()
    ->add($post)
    ->add(Post::all());
```

This way you can add all your pages super fast without the need to crawl them all.

## Support us

[<img src="https://github-ads.s3.eu-central-1.amazonaws.com/laravel-sitemap.jpg?t=1" width="419px" />](https://spatie.be/github-ad-click/laravel-sitemap)

We invest a lot of resources into creating [best in class open source packages](https://spatie.be/open-source). You can support us by [buying one of our paid products](https://spatie.be/open-source/support-us).

We highly appreciate you sending us a postcard from your hometown, mentioning which of our package(s) you are using. You'll find our address on [our contact page](https://spatie.be/about-us). We publish all received postcards on [our virtual postcard wall](https://spatie.be/open-source/postcards).

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
php artisan vendor:publish --provider="Spatie\Sitemap\SitemapServiceProvider" --tag=sitemap-config
```

This will copy the default config to `config/sitemap.php` where you can edit it.

```php
use GuzzleHttp\RequestOptions;
use Spatie\Sitemap\Crawler\Profile;

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
    ],
    
    /*
     * The sitemap generator can execute JavaScript on each page so it will
     * discover links that are generated by your JS scripts. This feature
     * is powered by headless Chrome.
     */
    'execute_javascript' => false,
    
    /*
     * The package will make an educated guess as to where Google Chrome is installed. 
     * You can also manually pass it's location here.
     */
    'chrome_binary_path' => '',

    /*
     * The sitemap generator uses a CrawlProfile implementation to determine
     * which urls should be crawled for the sitemap.
     */
    'crawl_profile' => Profile::class,
    
];
```

## Usage

### Generating a sitemap

The easiest way is to crawl the given domain and generate a sitemap with all found links.
The destination of the sitemap should be specified by `$path`.

```php
SitemapGenerator::create('https://example.com')->writeToFile($path);
```

The generated sitemap will look similar to this:

```xml
<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <url>
        <loc>https://example.com</loc>
        <lastmod>2016-01-01T00:00:00+00:00</lastmod>
    </url>
    <url>
        <loc>https://example.com/page</loc>
        <lastmod>2016-01-01T00:00:00+00:00</lastmod>
    </url>

    ...
</urlset>
```

### Customizing the sitemap generator

#### Define a custom Crawl Profile

You can create a custom crawl profile by implementing the `Spatie\Crawler\CrawlProfiles\CrawlProfile` interface and by customizing the `shouldCrawl()` method for full control over what url/domain/sub-domain should be crawled:

```php
use Spatie\Crawler\CrawlProfiles\CrawlProfile;
use Psr\Http\Message\UriInterface;

class CustomCrawlProfile extends CrawlProfile
{
    public function shouldCrawl(UriInterface $url): bool
    {
        if ($url->getHost() !== 'localhost') {
            return false;
        }
        
        return $url->getPath() === '/';
    }
}
```

and register your `CustomCrawlProfile::class` in `config/sitemap.php`.

```php
return [
    ...
    /*
     * The sitemap generator uses a CrawlProfile implementation to determine
     * which urls should be crawled for the sitemap.
     */
    'crawl_profile' => CustomCrawlProfile::class,
    
];
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

**Note:** `shouldCrawl` will only work with the default crawl `Profile` or custom crawl profiles that implement a `shouldCrawlCallback` method. 
 
```php
use Spatie\Sitemap\SitemapGenerator;
use Psr\Http\Message\UriInterface;

SitemapGenerator::create('https://example.com')
    ->shouldCrawl(function (UriInterface $url) {
        // All pages will be crawled, except the contact page.
        // Links present on the contact page won't be added to the
        // sitemap unless they are present on a crawlable page.
       
        return strpos($url->getPath(), '/contact') === false;
    })
    ->writeToFile($sitemapPath);
```

#### Configuring the crawler

The crawler itself can be [configured](https://github.com/spatie/crawler#usage) to do a few different things.

You can configure the crawler used by the sitemap generator, for example: to ignore robot checks; like so.

```php
SitemapGenerator::create('http://localhost:4020')
    ->configureCrawler(function (Crawler $crawler) {
        $crawler->ignoreRobots();
    })
    ->writeToFile($file);
```

#### Limiting the amount of pages crawled

You can limit the amount of pages crawled by calling `setMaximumCrawlCount`

```php
use Spatie\Sitemap\SitemapGenerator;

SitemapGenerator::create('https://example.com')
    ->setMaximumCrawlCount(500) // only the 500 first pages will be crawled
    ...
```

#### Executing Javascript

   
The sitemap generator can execute JavaScript on each page so it will discover links that are generated by your JS scripts. You can enable this feature by setting `execute_javascript` in the config file to `true`.

Under the hood, [headless Chrome](https://github.com/spatie/browsershot) is used to execute JavaScript. Here are some pointers on [how to install it on your system](https://spatie.be/docs/browsershot/v4/requirements).

The package will make an educated guess as to where Chrome is installed on your system. You can also manually pass the location of the Chrome binary to  `executeJavaScript()`.

#### Manually adding links

You can manually add links to a sitemap:

```php
use Spatie\Sitemap\SitemapGenerator;
use Spatie\Sitemap\Tags\Url;

SitemapGenerator::create('https://example.com')
    ->getSitemap()
    // here we add one extra link, but you can add as many as you'd like
    ->writeToFile($sitemapPath);
```

#### Adding alternates to links

Multilingual sites may have several alternate versions of the same page (one per language). Based on the previous example adding an alternate can be done as follows:

```php
use Spatie\Sitemap\SitemapGenerator;
use Spatie\Sitemap\Tags\Url;

SitemapGenerator::create('https://example.com')
    ->getSitemap()
    // here we add one extra link, but you can add as many as you'd like
    ->writeToFile($sitemapPath);
```

Note the ```addAlternate``` function which takes an alternate URL and the locale it belongs to.

#### Adding images to links

Urls can also have images. See also https://developers.google.com/search/docs/advanced/sitemaps/image-sitemaps

```php
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

Sitemap::create()
    // here we add an image to a URL
    ->add(Url::create('https://example.com')->addImage('https://example.com/images/home.jpg', 'Home page image'))
    ->writeToFile($sitemapPath);
```

#### Adding videos to links

As well as images, videos can be wrapped by URL tags. See https://developers.google.com/search/docs/crawling-indexing/sitemaps/video-sitemaps

You can set required attributes like so:

```php
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

Sitemap::create()
    ->add(
        Url::create('https://example.com')
            ->addVideo('https://example.com/images/thumbnail.jpg', 'Video title', 'Video Description', 'https://example.com/videos/source.mp4', 'https://example.com/video/123')
    )
    ->writeToFile($sitemapPath);
```

If you want to pass the optional parameters like `family_friendly`, `live`, or `platform`:

```php
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;
use Spatie\Sitemap\Tags\Video;


$options = ['family_friendly' => Video::OPTION_YES, 'live' => Video::OPTION_NO];
$allowOptions = ['platform' => Video::OPTION_PLATFORM_MOBILE];
$denyOptions = ['restriction' => 'CA'];

Sitemap::create()
    ->add(
        Url::create('https://example.com')
            ->addVideo('https://example.com/images/thumbnail.jpg', 'Video title', 'Video Description', 'https://example.com/videos/source.mp4', 'https://example.com/video/123', $options, $allowOptions, $denyOptions)
    )
    ->writeToFile($sitemapPath);
```

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

### Create a sitemap index with sub-sequent sitemaps

You can call the `maxTagsPerSitemap` method to generate a
sitemap that only contains the given amount of tags

```php
use Spatie\Sitemap\SitemapGenerator;

SitemapGenerator::create('https://example.com')
    ->maxTagsPerSitemap(20000)
    ->writeToFile(public_path('sitemap.xml'));

```

## Generating the sitemap frequently

Your site will probably be updated from time to time. In order to let your sitemap reflect these changes, you can run the generator periodically. The easiest way of doing this is to make use of Laravel's default scheduling capabilities.

You could set up an artisan command much like this one:

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

First start the test server in a separate terminal session:

``` bash
cd tests/server
./start_server.sh
```

With the server running you can execute the tests:

``` bash
$ composer test
```

## Contributing

Please see [CONTRIBUTING](https://github.com/spatie/.github/blob/main/CONTRIBUTING.md) for details.

## Security

If you've found a bug regarding security please mail [security@spatie.be](mailto:security@spatie.be) instead of using the issue tracker.

## Credits

- [Freek Van der Herten](https://github.com/freekmurze)
- [All Contributors](../../contributors)

## Support us

Spatie is a webdesign agency based in Antwerp, Belgium. You'll find an overview of all our open source projects [on our website](https://spatie.be/opensource).

Does your business depend on our contributions? Reach out and support us on [Patreon](https://www.patreon.com/spatie). 
All pledges will be dedicated to allocating workforce on maintenance and new awesome stuff.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
