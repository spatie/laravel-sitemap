---
title: Introduction
weight: 1
---

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

Now you can add a single post model to the sitemap or even a whole collection.

```php
use Spatie\Sitemap\Sitemap;

Sitemap::create()
    ->add($post)
    ->add(Post::all());
```

## We got badges

[![Latest Version on Packagist](https://img.shields.io/packagist/v/spatie/laravel-sitemap.svg?style=flat-square)](https://packagist.org/packages/spatie/laravel-sitemap)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Test Status](https://img.shields.io/github/actions/workflow/status/spatie/laravel-sitemap/run-tests.yml?label=tests)](https://github.com/spatie/laravel-sitemap/actions/workflows/run-tests.yml)
[![Code Style Status](https://img.shields.io/github/actions/workflow/status/spatie/laravel-sitemap/fix-php-code-style-issues.yml?label=code%20style)](https://github.com/spatie/laravel-sitemap/actions/workflows/fix-php-code-style-issues.yml)
[![PHPStan](https://img.shields.io/github/actions/workflow/status/spatie/laravel-sitemap/phpstan.yml?label=PHPStan)](https://github.com/spatie/laravel-sitemap/actions/workflows/phpstan.yml)
[![Total Downloads](https://img.shields.io/packagist/dt/spatie/laravel-sitemap.svg?style=flat-square)](https://packagist.org/packages/spatie/laravel-sitemap)
