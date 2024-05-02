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

    @each('sitemap::image', $tag->images, 'image')
    @each('sitemap::video', $tag->videos, 'video')
    @each('sitemap::news', $tag->news, 'news')
</url>
