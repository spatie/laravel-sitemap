<video:video>
    <video:thumbnail_loc>{{ $video->thumbnailLoc }}</video:thumbnail_loc>
    <video:title>{{ $video->title }}</video:title>
    <video:description>{{ $video->description }}</video:description>
@if ($video->contentLoc)
    <video:content_loc>{{ $video->contentLoc }}</video:content_loc>
@endif
@if ($video->playerLoc)
    <video:player_loc>{{ $video->playerLoc }}</video:player_loc>
@endif
@foreach($video->options as $tag => $value)
    <video:{{$tag}}>{{$value}}</video:{{$tag}}>
@endforeach
@foreach($video->allow as $tag => $value)
    <video:{{$tag}} relationship="allow">{{$value}}</video:{{$tag}}>
@endforeach
@foreach($video->deny as $tag => $value)
    <video:{{$tag}} relationship="deny">{{$value}}</video:{{$tag}}>
@endforeach
</video:video>
