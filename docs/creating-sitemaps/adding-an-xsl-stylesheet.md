---
title: Adding an XSL stylesheet
weight: 5
---

You can add an XSL stylesheet processing instruction to make your sitemaps human readable in browsers. Call `setStylesheet` on either a `Sitemap` or `SitemapIndex`:

```php
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\SitemapIndex;

Sitemap::create()
    ->setStylesheet('/sitemap.xsl')
    ->add('/page1')
    ->writeToFile($sitemapPath);

SitemapIndex::create()
    ->setStylesheet('/sitemap-index.xsl')
    ->add('/pages_sitemap.xml')
    ->writeToFile($sitemapIndexPath);
```

When using `maxTagsPerSitemap` on a `Sitemap` with a stylesheet set, the stylesheet will be propagated to both the generated index and all chunk sitemaps.
