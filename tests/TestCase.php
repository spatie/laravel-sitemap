<?php

namespace Spatie\Sitemap\Test;

use File;
use Carbon\Carbon;
use Spatie\Snapshots\MatchesSnapshots;
use Spatie\Sitemap\SitemapServiceProvider;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

abstract class TestCase extends OrchestraTestCase
{
    use MatchesSnapshots;

    /** @var \Carbon\Carbon */
    protected $now;

    public function setUp()
    {
        parent::setUp();

        $this->now = Carbon::create('2016', '1', '1', '0', '0', '0');

        Carbon::setTestNow($this->now);

        $this->initializeTempDirectory();
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

    public function getTempDirectory($path = ''): string
    {
        if ($path) {
            $path = "/{$path}";
        }

        return __DIR__.'/temp'.$path;
    }

    protected function initializeTempDirectory()
    {
        $this->initializeDirectory($this->getTempDirectory());

        file_put_contents($this->getTempDirectory().'/.gitignore', '*'.PHP_EOL.'!.gitignore');
    }

    protected function initializeDirectory($directory)
    {
        if (File::isDirectory($directory)) {
            File::deleteDirectory($directory);
        }
        File::makeDirectory($directory);
    }
}
