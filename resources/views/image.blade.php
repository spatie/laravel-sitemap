<image:image>
@if (! empty($image->url))
    <image:loc>{{ url($image->url) }}</image:loc>
@endif
@if (! empty($image->caption))
    <image:caption>{{ $image->caption }}</image:caption>
@endif
@if (! empty($image->geo_location))
    <image:geo_location>{{ $image->geo_location }}</image:geo_location>
@endif
@if (! empty($image->title))
    <image:title>{{ $image->title }}</image:title>
@endif
@if (! empty($image->license))
    <image:license>{{ $image->license }}</image:license>
@endif
</image:image>
