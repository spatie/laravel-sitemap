<video:video>
    <video:thumbnail_loc>{{ url($video->thumbnailLoc) }}</video:thumbnail_loc>
    <video:title>{{ url($video->title) }}</video:title>
    <video:description>{{ url($video->description) }}</video:description>
@if ($video->contentLoc)
    <video:content_loc>{{ url($video->contentLoc) }}</video:content_loc>
@endif
@if ($video->playerLoc)
    <video:player_loc>{{ url($video->playerLoc) }}</video:player_loc>
@endif
@if ($video->platforms && count($video->platforms) > 0)
    <video:platform relationship="allow">{{ implode($video->playerLoc, " ") }}</video:platform>
@endif
</video:video>
