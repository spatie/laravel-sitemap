---
title: News
weight: 4
---

You can add Google News tags to URLs. See the [Google News Sitemaps documentation](https://developers.google.com/search/docs/crawling-indexing/sitemaps/news-sitemap) for more information.

```php
use Carbon\Carbon;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

Sitemap::create()
    ->add(
        Url::create('https://example.com/news-article')
            ->addNews('Publication Name', 'en', 'Article title', Carbon::now())
    )
    ->writeToFile($sitemapPath);
```

You can also pass optional parameters like `access` and `genres`:

```php
use Carbon\Carbon;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\News;
use Spatie\Sitemap\Tags\Url;

$options = [
    'access' => News::OPTION_ACCESS_SUB,
    'genres' => implode(', ', [News::OPTION_GENRES_BLOG, News::OPTION_GENRES_OPINION]),
];

Sitemap::create()
    ->add(
        Url::create('https://example.com/news-article')
            ->addNews('Publication Name', 'en', 'Article title', Carbon::now(), $options)
    )
    ->writeToFile($sitemapPath);
```
