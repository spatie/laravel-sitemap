---
title: Manually creating a sitemap
weight: 2
---

You can create a sitemap entirely by hand, without crawling:

```php
use Carbon\Carbon;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

Sitemap::create()
    ->add('/page1')
    ->add('/page2')
    ->add(Url::create('/page3')->setLastModificationDate(Carbon::create('2016', '1', '1')))
    ->writeToFile($sitemapPath);
```

You can check if the sitemap contains a specific URL and retrieve it:

```php
$sitemap->hasUrl('/page2'); // returns true or false
$sitemap->getUrl('/page2'); // returns a Url object or null
```
