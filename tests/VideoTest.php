<?php

use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;
use Spatie\Sitemap\Tags\Video;

test('XML has Video tag', function () {
    $expected_xml = '<?xml version="1.0" encoding="UTF-8"?>
                        <urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xhtml="http://www.w3.org/1999/xhtml" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1" xmlns:video="http://www.google.com/schemas/sitemap-video/1.1">
                            <url>
                                <loc>https://example.com</loc>
                                <changefreq>daily</changefreq>
                                <priority>0.8</priority>
                                <video:video>
                                    <video:thumbnail_loc>https://example.com/image.jpg</video:thumbnail_loc>
                                    <video:title>My Test Title</video:title>
                                    <video:description>My Test Description</video:description>
                                    <video:content_loc>https://example.com/video.mp4</video:content_loc>
                                    <video:live>no</video:live>
                                    <video:family_friendly>yes</video:family_friendly>
                                    <video:platform relationship="allow">mobile</video:platform>
                                    <video:restriction relationship="deny">CA</video:restriction>
                                    <video:tag>tag1</video:tag>
                                    <video:tag>tag2</video:tag>
                                </video:video>
                            </url>
                        </urlset>';

    $options = ["live" => "no", "family_friendly" => "yes"];
    $allow = ["platform" => Video::OPTION_PLATFORM_MOBILE];
    $deny = ["restriction" => 'CA'];
    $tags = ['tag1', 'tag2'];
    $sitemap = Sitemap::create()
        ->add(
            Url::create("https://example.com")
                ->addVideo("https://example.com/image.jpg", "My Test Title", "My Test Description", "https://example.com/video.mp4", null, $options, $allow, $deny, $tags)
        );

    $render_output = $sitemap->render();

    expect($render_output)->toEqualXmlString($expected_xml);
});
