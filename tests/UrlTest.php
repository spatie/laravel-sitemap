<?php

use Carbon\Carbon;
use Spatie\Sitemap\Tags\Alternate;
use Spatie\Sitemap\Tags\Url;

beforeEach(function () {
    $this->now = Carbon::now();

    Carbon::setTestNow($this->now);

    $this->url = new Url('testUrl');
});

it('provides a `create` method', function () {
    $url = Url::create('testUrl');

    expect($url->url)->toEqual('testUrl');
});

it(
    'will use the current date time as the default for last modification date',
    function () {
        expect($this->url->lastModificationDate->toAtomString())
            ->toEqual($this->now->toAtomString());
    }
);

test('url can be set', function () {
    $url = Url::create('defaultUrl');

    $url->setUrl('testUrl');

    expect($url->url)->toEqual('testUrl');
});

test('last modification date can be set', function () {
    $carbon = Carbon::now()->subDay();

    $this->url->setLastModificationDate($carbon);

    expect($this->url->lastModificationDate->toAtomString())
        ->toEqual($carbon->toAtomString());
});

test('priority can be set')
    ->tap(fn () => $this->url->setPriority(0.1))
    ->expect(fn () => $this->url->priority)
    ->toEqual(0.1);

test('priority is clamped')
    ->tap(fn () => $this->url->setPriority(-0.1))
    ->expect(fn () => $this->url->priority)
    ->toEqual(0)
    ->tap(fn () => $this->url->setPriority(1.1))
    ->expect(fn () => $this->url->priority)
    ->toEqual(1);

test('change frequency can be set')
    ->tap(fn () => $this->url->setChangeFrequency(Url::CHANGE_FREQUENCY_YEARLY))
    ->expect(fn () => $this->url->changeFrequency)
    ->toEqual(Url::CHANGE_FREQUENCY_YEARLY);

test('alternate can be added', function () {
    $url = 'defaultUrl';
    $locale = 'en';

    $this->url->addAlternate($url, $locale);

    expect($this->url->alternates[0])->toEqual(new Alternate($url, $locale));
});

it('can determine its type')
    ->expect(fn () => $this->url->getType())
    ->toEqual('url');

it('can determine the path', function () {
    $path = '/part1/part2/part3';

    expect($path)
        ->toEqual(Url::create('http://example.com/part1/part2/part3')->path())
        ->toEqual(Url::create('/part1/part2/part3')->path());
});

it('can get all segments from a relative url', function () {
    $segments = [
        'part1',
        'part2',
        'part3',
    ];

    expect(Url::create('/part1/part2/part3')->segments())
        ->toMatchArray($segments);
});

it('can get all segments from an absolute url', function () {
    $segments = [
        'part1',
        'part2',
        'part3',
    ];

    expect(Url::create('http://example.com/part1/part2/part3')->segments())
        ->toMatchArray($segments);
});

it('can get a specific segment')
    ->expect('part2')
    ->toEqual(Url::create('http://example.com/part1/part2/part3')->segment(2))
    ->toEqual(Url::create('http://example.com/part1/part2/part3')->segments(2));

it('will return null for non-existing segment')
    ->expect(Url::create('http://example.com/part1/part2/part3')->segment(5))
    ->toBeNull();
