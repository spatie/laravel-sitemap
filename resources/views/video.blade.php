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
@if ($video->platforms && count($video->platforms) > 0)
    <video:platform relationship="allow">{{ implode(" ", $video->platforms) }}</video:platform>
@endif
</video:video>
