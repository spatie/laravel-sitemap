<image:image>
@if(! empty($tag->url))
    <image:loc>{{$tag->url}}</image:loc>
    @endif
@if(! empty($tag->caption))
    <image:caption>{{$tag->caption}}</image:caption>
    @endif
@if(! empty($tag->geoLocation))
    <image:geo_location>{{$tag->geoLocation}}</image:geo_location>
    @endif
@if(! empty($tag->title))
    <image:title>{{$tag->title}}</image:title>
    @endif
@if(! empty($tag->license))
    <image:license>{{$tag->license}}</image:license>
    @endif
</image:image>