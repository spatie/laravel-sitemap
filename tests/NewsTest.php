<?php

use Carbon\Carbon;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\News;
use Spatie\Sitemap\Tags\Url;

test('XML has News tag', function () {
    $publicationDate = Carbon::now();
    $expected_xml = '<?xml version="1.0" encoding="UTF-8"?>
                        <urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xhtml="http://www.w3.org/1999/xhtml" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1" xmlns:video="http://www.google.com/schemas/sitemap-video/1.1" xmlns:news="http://www.google.com/schemas/sitemap-news/0.9">
                            <url>
                                <loc>https://example.com</loc>
                                <changefreq>daily</changefreq>
                                <priority>0.8</priority>
                                <news:news>
                                    <news:publication>
                                        <news:name>News name</news:name>
                                        <news:language>en</news:language>
                                    </news:publication>
                                    <news:title>New news article</news:title>
                                    <news:publication_date>'.$publicationDate->toW3cString().'</news:publication_date>
                                    <news:access>Subscription</news:access>
                                    <news:genres>Blog, UserGenerated</news:genres>
                                </news:news>
                            </url>
                        </urlset>';

    $options = [
        'access' => News::OPTION_ACCESS_SUB,
        'genres' => implode(', ', [News::OPTION_GENRES_BLOG, News::OPTION_GENRES_UG])
    ];
    $sitemap = Sitemap::create()
        ->add(
            Url::create("https://example.com")
                ->addNews('News name', 'en', 'New news article', $publicationDate, $options)
        );

    $render_output = $sitemap->render();

    expect($render_output)->toEqualXmlString($expected_xml);
});
