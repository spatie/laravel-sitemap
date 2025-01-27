<image:image>
@if (! empty($image->url))
    <image:loc>{{ url($image->url) }}</image:loc>
@endif
@if (! empty($image->license))
    <image:license>{{ $image->license }}</image:license>
@endif
</image:image>
