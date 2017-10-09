<url>
    @if (! empty($tag->url))
    <loc>{{ $tag->url }}</loc>
    @endif
    @foreach ($tag->alternates as $alternate)
    <xhtml:link rel="alternate" hreflang="{{ $alternate->locale }}" href="{{ $alternate->url }}" />
    @endforeach
@if (! empty($tag->lastModificationDate))
<lastmod>{{ $tag->lastModificationDate->format(config('sitemap.last_modified_date_format')) }}</lastmod>
@endif
    @if (! empty($tag->changeFrequency))
    <changefreq>{{ $tag->changeFrequency }}</changefreq>
    @endif
@if (! empty($tag->priority))
    <priority>{{ $tag->priority }}</priority>
@endif
    </url>
