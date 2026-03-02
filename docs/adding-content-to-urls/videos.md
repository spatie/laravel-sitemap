---
title: Videos
weight: 3
---

URLs can have video metadata. See the [Google Video Sitemaps documentation](https://developers.google.com/search/docs/crawling-indexing/sitemaps/video-sitemaps) for more information.

You can set the required attributes like so:

```php
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

Sitemap::create()
    ->add(
        Url::create('https://example.com')
            ->addVideo(
                'https://example.com/images/thumbnail.jpg',
                'Video title',
                'Video Description',
                'https://example.com/videos/source.mp4',
                'https://example.com/video/123',
            )
    )
    ->writeToFile($sitemapPath);
```

If you want to pass the optional parameters like `family_friendly`, `live`, `platform`, or `tags`:

```php
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;
use Spatie\Sitemap\Tags\Video;

$options = ['family_friendly' => Video::OPTION_YES, 'live' => Video::OPTION_NO];
$allowOptions = ['platform' => Video::OPTION_PLATFORM_MOBILE];
$denyOptions = ['restriction' => 'CA'];
$tags = ['cooking', 'recipes'];

Sitemap::create()
    ->add(
        Url::create('https://example.com')
            ->addVideo(
                'https://example.com/images/thumbnail.jpg',
                'Video title',
                'Video Description',
                'https://example.com/videos/source.mp4',
                'https://example.com/video/123',
                $options,
                $allowOptions,
                $denyOptions,
                $tags,
            )
    )
    ->writeToFile($sitemapPath);
```
