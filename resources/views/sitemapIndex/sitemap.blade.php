<sitemap>
    @if (! empty($tag->url))
    <loc>{{ $tag->url }}</loc>
    @endif

    @if (! empty($tag->lastModificationDate))
    <lastmod>{{ $tag->lastModificationDate->format(DateTime::ATOM) }}</lastmod>
    @endif
</sitemap>
