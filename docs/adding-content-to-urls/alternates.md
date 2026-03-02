---
title: Alternates
weight: 1
---

Multilingual sites may have several alternate versions of the same page (one per language). You can add alternates using the `addAlternate` method, which takes an alternate URL and the locale it belongs to.

```php
use Spatie\Sitemap\SitemapGenerator;
use Spatie\Sitemap\Tags\Url;

SitemapGenerator::create('https://example.com')
    ->getSitemap()
    ->add(
        Url::create('/extra-page')
            ->addAlternate('/extra-pagina', 'nl')
            ->addAlternate('/page-supplementaire', 'fr')
    )
    ->writeToFile($sitemapPath);
```
