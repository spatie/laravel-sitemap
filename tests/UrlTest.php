<?php

namespace Spatie\Sitemap\Test;

use Carbon\Carbon;
use Spatie\Sitemap\Tags\Url;

class UrlTest extends TestCase
{
    /** @var \Spatie\Sitemap\Tags\Url */
    protected $url;

    public function setUp()
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
    public function last_modification_date_can_be_set()
    {
        $carbon = Carbon::now()->subDay();

        $this->url->lastModificationDate($carbon);

        $this->assertEquals($carbon->toAtomString(), $this->url->lastModificationDate->toAtomString());
    }

    public function priority_can_be_set()
    {
        $this->url->priority(0.1);

        $this->assertEquals(0.1, $this->url->priority);
    }

    public function change_frequency_can_be_set()
    {
        $this->url->changeFrequency(Url::CHANGE_FREQUENCY_YEARLY);

        $this->assertEquals(Url::CHANGE_FREQUENCY_YEARLY, $this->url->changeFrequency);
    }

    /** @test */
    public function it_can_determine_its_type()
    {
        $this->assertEquals('url', $this->url->getType());
    }
}
