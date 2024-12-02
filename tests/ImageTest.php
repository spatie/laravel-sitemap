<?php

use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

test('XML has image', function () {
    $expected_xml = '<?xml version="1.0" encoding="UTF-8"?>
                        <urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xhtml="http://www.w3.org/1999/xhtml" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1" xmlns:video="http://www.google.com/schemas/sitemap-video/1.1" xmlns:news="http://www.google.com/schemas/sitemap-news/0.9">
                            <url>
                                <loc>https://localhost</loc>
                                <image:image>
                                    <image:loc>https://localhost/favicon.ico</image:loc>
                                    <image:caption>Favicon</image:caption>
                                </image:image>
                            </url>
                        </urlset>';

    $sitemap = Sitemap::create();
    $url = Url::create('https://localhost')->addImage('https://localhost/favicon.ico', 'Favicon');
    $sitemap->add($url);

    $render_output = $sitemap->render();

    expect($render_output)->toEqualXmlString($expected_xml);
});
