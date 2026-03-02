---
title: Sitemapable models
weight: 5
---

You can add your models directly by implementing the `Sitemapable` interface.

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

If you need more control, you can return a `Url` object instead:

```php
use Carbon\Carbon;
use Spatie\Sitemap\Contracts\Sitemapable;
use Spatie\Sitemap\Tags\Url;

class Post extends Model implements Sitemapable
{
    public function toSitemapTag(): Url | string | array
    {
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

This way you can add all your pages without the need to crawl them.
