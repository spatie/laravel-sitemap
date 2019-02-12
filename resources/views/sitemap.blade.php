<?= '<'.'?'.'xml version="1.0" encoding="UTF-8"?>'."\n"; ?>
<urlset <?= $namespaces ?> >

@foreach($tags as $tag)
    @include('laravel-sitemap::' . $tag->getType())
@endforeach

</urlset>