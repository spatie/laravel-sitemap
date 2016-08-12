<url>
    @if (! empty($entry->url))
        <loc>{{ $entry->url }}</loc>
    @endif

    @if (! empty($entry->lastModificationDate))
        <lastmod>{{ $entry->lastModificationDate->toAtomString() }}</lastmod>
    @endif

    @if (! empty($entry->changeFrequency))
        <changefreq>{{ $entry->changeFrequency }}</changefreq>
    @endif

    @if (! empty($entry->priority))
        <priority>{{ $entry->priority }}</priority>
    @endif

</url>
