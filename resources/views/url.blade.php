<url>
    @if (! empty($tag->url))
    <loc>{{ url($tag->url) }}</loc>
    @endif
    @if (count($tag->alternates))
    @foreach ($tag->alternates as $alternate)
    <xhtml:link rel="alternate" hreflang="{{ $alternate->locale }}" href="{{ url($alternate->url) }}" />
    @endforeach
    @endif
    @if (! empty($tag->lastModificationDate))
    <lastmod>{{ $tag->lastModificationDate->format(DateTime::ATOM) }}</lastmod>
    @endif
    @if (! empty($tag->changeFrequency))
    <changefreq>{{ $tag->changeFrequency }}</changefreq>
    @endif
    @if (! empty($tag->priority))
    <priority>{{ number_format($tag->priority,1) }}</priority>
    @endif
    @each('sitemap::image', $tag->images, 'image')

    @if(! empty($tag->news))
    <news:news>
        @if(! empty($tag->news['publication']))
        <news:publication>
            @if(isset($tag->news['publication']['name']))
            <news:name>
                {{$tag->news['publication']['name']}}
            </news:name>
            @endif

            @if(isset($tag->news['publication']['language']))
            <news:language>
                {{$tag->news['publication']['name']}}
            </news:language>

            @endif
        </news:publication>
        @endif

        @if(isset($tag->news['publication_date'])
        <news:publication_date>
            {{$tag->news['publication_date']}}
        </news:publication_date>
        @endif

        @if(isset($tag->news['title']))
        <news:title>
            {{$tag->news['publication_date']}}
        </news:title>
        @endif

    </news:news>
    @endif
</url>
