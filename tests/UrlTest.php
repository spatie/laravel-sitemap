<?php

namespace Spatie\Sitemap\Test;

use Carbon\Carbon;
use Spatie\Sitemap\Tags\Alternate;
use Spatie\Sitemap\Tags\Url;

class UrlTest extends TestCase
{
    /** @var \Spatie\Sitemap\Tags\Url */
    protected $url;

    public function setUp(): void
    {
        parent::setUp();

        $this->now = Carbon::now();

        Carbon::setTestNow($this->now);

        $this->url = new Url('testUrl');
    }

    /** @test */
    public function it_provides_a_create_method()
    {
        $url = Url::create('testUrl');

        $this->assertEquals('testUrl', $url->url);
    }

    /** @test */
    public function it_will_use_the_current_date_time_as_the_default_for_last_modification_date()
    {
        $this->assertEquals($this->now->toAtomString(), $this->url->lastModificationDate->toAtomString());
    }

    /** @test */
    public function url_can_be_set()
    {
        $url = Url::create('defaultUrl');

        $url->setUrl('testUrl');

        $this->assertEquals('testUrl', $url->url);
    }

    /** @test */
    public function last_modification_date_can_be_set()
    {
        $carbon = Carbon::now()->subDay();

        $this->url->setLastModificationDate($carbon);

        $this->assertEquals($carbon->toAtomString(), $this->url->lastModificationDate->toAtomString());
    }

    public function priority_can_be_set()
    {
        $this->url->setPriority(0.1);

        $this->assertEquals(0.1, $this->url->priority);
    }

    /** @test */
    public function priority_is_clamped()
    {
        $this->url->setPriority(-0.1);

        $this->assertEquals(0, $this->url->priority);

        $this->url->setPriority(1.1);

        $this->assertEquals(1, $this->url->priority);
    }

    public function change_frequency_can_be_set()
    {
        $this->url->setChangeFrequency(Url::CHANGE_FREQUENCY_YEARLY);

        $this->assertEquals(Url::CHANGE_FREQUENCY_YEARLY, $this->url->changeFrequency);
    }

    /** @test */
    public function alternate_can_be_added()
    {
        $url = 'defaultUrl';
        $locale = 'en';

        $this->url->addAlternate($url, $locale);

        $this->assertEquals(new Alternate($url, $locale), $this->url->alternates[0]);
    }

    /** @test */
    public function it_can_determine_its_type()
    {
        $this->assertEquals('url', $this->url->getType());
    }

    /** @test */
    public function it_can_determine_the_path()
    {
        $path = '/part1/part2/part3';

        $this->assertEquals($path, Url::create('http://example.com/part1/part2/part3')->path());
        $this->assertEquals($path, Url::create('/part1/part2/part3')->path());
    }

    /** @test */
    public function it_can_get_all_segments_from_a_relative_url()
    {
        $segments = [
            'part1',
            'part2',
            'part3',
        ];

        $this->assertEquals($segments, Url::create('/part1/part2/part3')->segments());
    }

    /** @test */
    public function it_can_get_all_segments_from_an_absolute_url()
    {
        $segments = [
            'part1',
            'part2',
            'part3',
        ];

        $this->assertEquals($segments, Url::create('http://example.com/part1/part2/part3')->segments());
    }

    /** @test */
    public function it_can_get_a_specific_segment()
    {
        $this->assertEquals('part2', Url::create('http://example.com/part1/part2/part3')->segment(2));
        $this->assertEquals('part2', Url::create('http://example.com/part1/part2/part3')->segments(2));
    }

    /** @test */
    public function it_will_return_null_for_a_non_existing_segment()
    {
        $this->assertNull(Url::create('http://example.com/part1/part2/part3')->segment(5));
    }
}
