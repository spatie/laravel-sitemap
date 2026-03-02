<div align="left">
    <a href="https://spatie.be/open-source?utm_source=github&utm_medium=banner&utm_campaign=laravel-sitemap">
      <picture>
        <source media="(prefers-color-scheme: dark)" srcset="https://spatie.be/packages/header/laravel-sitemap/html/dark.webp">
        <img alt="Logo for laravel-sitemap" src="https://spatie.be/packages/header/laravel-sitemap/html/light.webp">
      </picture>
    </a>

<h1>Generate sitemaps with ease</h1>

[![Latest Version on Packagist](https://img.shields.io/packagist/v/spatie/laravel-sitemap.svg?style=flat-square)](https://packagist.org/packages/spatie/laravel-sitemap)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Test Status](https://img.shields.io/github/actions/workflow/status/spatie/laravel-sitemap/run-tests.yml?label=tests)](https://github.com/spatie/laravel-sitemap/actions/workflows/run-tests.yml)
[![Code Style Status](https://img.shields.io/github/actions/workflow/status/spatie/laravel-sitemap/fix-php-code-style-issues.yml?label=code%20style)](https://github.com/spatie/laravel-sitemap/actions/workflows/fix-php-code-style-issues.yml)
[![PHPStan](https://img.shields.io/github/actions/workflow/status/spatie/laravel-sitemap/phpstan.yml?label=PHPStan)](https://github.com/spatie/laravel-sitemap/actions/workflows/phpstan.yml)
[![Total Downloads](https://img.shields.io/packagist/dt/spatie/laravel-sitemap.svg?style=flat-square)](https://packagist.org/packages/spatie/laravel-sitemap)

</div>

This package can generate a sitemap without you having to add URLs to it manually. This works by crawling your entire site.

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
use Spatie\Sitemap\SitemapGenerator;
use Spatie\Sitemap\Tags\Url;

SitemapGenerator::create('https://example.com')
    ->getSitemap()
    ->add(Url::create('/extra-page')
        ->setLastModificationDate(Carbon::yesterday()))
    ->add(...)
    ->writeToFile($path);
```

You can also add your models directly by implementing the `Sitemapable` interface.

```php
use Spatie\Sitemap\Contracts\Sitemapable;
use Spatie\Sitemap\Tags\Url;

class Post extends Model implements Sitemapable
{
    public function toSitemapTag(): Url | string | array
    {
        return route('blog.post.show', $this);
    }
}
```

## Support us

[<img src="https://github-ads.s3.eu-central-1.amazonaws.com/laravel-sitemap.jpg?t=1" width="419px" />](https://spatie.be/github-ad-click/laravel-sitemap)

We invest a lot of resources into creating [best in class open source packages](https://spatie.be/open-source). You can support us by [buying one of our paid products](https://spatie.be/open-source/support-us).

We highly appreciate you sending us a postcard from your hometown, mentioning which of our package(s) you are using. You'll find our address on [our contact page](https://spatie.be/about-us). We publish all received postcards on [our virtual postcard wall](https://spatie.be/open-source/postcards).

## Documentation

All documentation is available [on our documentation site](https://spatie.be/docs/laravel-sitemap).

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](https://github.com/spatie/.github/blob/main/CONTRIBUTING.md) for details.

## Security

If you've found a bug regarding security please mail [security@spatie.be](mailto:security@spatie.be) instead of using the issue tracker.

## Credits

- [Freek Van der Herten](https://github.com/freekmurze)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
