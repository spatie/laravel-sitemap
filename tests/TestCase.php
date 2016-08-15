<?php

namespace Spatie\Sitemap\Test;

use File;
use Orchestra\Testbench\TestCase as OrchestraTestCase;
use Spatie\Sitemap\SitemapServiceProvider;

abstract class TestCase extends OrchestraTestCase
{
    public function setUp()
    {
        parent::setUp();

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

    public function getTempDirectory($path = '')
    {
        if ($path) {
            $path = "/{$path}";
        }

        return __DIR__.'/temp' . $path;
    }

    protected function initializeTempDirectory()
    {
        $this->initializeDirectory($this->getTempDirectory());

        file_put_contents($this->getTempDirectory() . '/.gitignore', '*' . PHP_EOL . '!.gitignore');
    }


    protected function initializeDirectory($directory)
    {
        if (File::isDirectory($directory)) {
            File::deleteDirectory($directory);
        }
        File::makeDirectory($directory);
    }
}
