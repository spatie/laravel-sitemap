<?php

use Spatie\Sitemap\Tags\Alternate;

beforeEach(function () {
    $this->alternate = new Alternate('defaultUrl', 'en');
});

test('url can be set', function () {
    $this->alternate->setUrl('testUrl');

    expect($this->alternate->url)->toEqual('testUrl');
});

test('locale can be set', function () {
    $this->alternate->setLocale('en');

    expect($this->alternate->locale)->toEqual('en');
});
