<?php

use Spatie\Crawler\CrawlProfiles\CrawlInternalUrls;
use Spatie\Crawler\CrawlProfiles\CrawlSubdomains;
use Spatie\Sitemap\Crawler\Profile;
use Spatie\Sitemap\Test\Crawler\CustomCrawlProfile;

it('can use default profile with callback', function () {
    $profile = new Profile('https://example.com');
    $profile->shouldCrawlCallback(fn (string $url) => parse_url($url, PHP_URL_HOST) === 'example.com');

    expect($profile->shouldCrawl('https://example.com/page'))->toBeTrue()
        ->and($profile->shouldCrawl('https://other.com/page'))->toBeFalse();
});

it('can use the custom profile', function () {
    $profile = new CustomCrawlProfile('http://localhost');

    expect($profile->shouldCrawl('http://localhost/'))->toBeTrue()
        ->and($profile->shouldCrawl('http://localhost/page'))->toBeFalse()
        ->and($profile->shouldCrawl('https://external.com/'))->toBeFalse();
});

it('can use the subdomain profile', function () {
    $profile = new CrawlSubdomains('https://example.com');

    expect($profile->shouldCrawl('https://sub.example.com/page'))->toBeTrue()
        ->and($profile->shouldCrawl('https://other.com/page'))->toBeFalse();
});

it('can use the internal profile', function () {
    $profile = new CrawlInternalUrls('https://example.com');

    expect($profile->shouldCrawl('https://example.com/page'))->toBeTrue()
        ->and($profile->shouldCrawl('https://other.com/page'))->toBeFalse();
});
