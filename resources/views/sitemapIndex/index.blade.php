<?= '<'.'?'.'xml version="1.0" encoding="UTF-8"?>'."\n" ?>
<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
@foreach($tags as $tag)
    @include('laravel-sitemap::sitemapIndex/' . $tag->getType())
@endforeach
</sitemapindex>
