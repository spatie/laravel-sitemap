<?= '<'.'?'.'xml version="1.0" encoding="UTF-8"?>'."\n"; ?>
@if(!empty($stylesheetUrl))
<?= '<'.'?'.'xml-stylesheet type="text/xsl" href="'.e($stylesheetUrl).'"?'.">\n"; ?>
@endif
<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
@foreach($tags as $tag)
    @include('sitemap::sitemapIndex/' . $tag->getType())
@endforeach
</sitemapindex>
