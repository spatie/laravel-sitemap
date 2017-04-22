<?php

namespace Spatie\Sitemap\Test;

use Carbon\Carbon;
use Spatie\Snapshots\MatchesSnapshots;
use Spatie\Sitemap\SitemapServiceProvider;
use Spatie\TemporaryDirectory\TemporaryDirectory;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

abstract class TestCase extends OrchestraTestCase
{
    use MatchesSnapshots;

    /** @var \Carbon\Carbon */
    protected $now;

    /** @var \Spatie\TemporaryDirectory\TemporaryDirectory */
    protected $temporaryDirectory;

    public function setUp()
    {
        parent::setUp();

        $this->now = Carbon::create('2016', '1', '1', '0', '0', '0');

        Carbon::setTestNow($this->now);

        $this->temporaryDirectory = (new TemporaryDirectory())->force()->create();
    }

    /**
     * @param \Illuminate\Foundation\Application $app
     *
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            SitemapServiceProvider::class,
        ];
    }
}
