<url>
    @if (! empty($tag->url))
    <loc>{{ $tag->url }}</loc>
    @endif

    @if (count($tag->alternates))
    @foreach ($tag->alternates as $alternate)
    <xhtml:link rel="alternate" hreflang="{{ $alternate->locale }}" href="{{ $alternate->url }}" />
    @endforeach
    @endif

    @if (! empty($tag->lastModificationDate))
    <lastmod>{{ $tag->lastModificationDate->format(DateTime::ATOM) }}</lastmod>
    @endif

    @if (! empty($tag->changeFrequency))
    <changefreq>{{ $tag->changeFrequency }}</changefreq>
    @endif

    @if (! empty($tag->priority))
    <priority>{{ $tag->priority }}</priority>
    @endif
</url>
