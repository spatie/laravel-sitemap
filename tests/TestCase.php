<?php

namespace Spatie\Sitemap\Test;

use Carbon\Carbon;
use File;
use Orchestra\Testbench\TestCase as OrchestraTestCase;
use Spatie\Sitemap\SitemapServiceProvider;

abstract class TestCase extends OrchestraTestCase
{
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

    protected function assertIsEqualToContentsOfStub($stubName, $actualOutput)
    {
        $expectedOutput = $this->getContentOfStub($stubName);

        $this->assertEquals($this->sanitizeHtmlWhitespace($expectedOutput), $this->sanitizeHtmlWhitespace($actualOutput));
    }

    protected function getContentOfStub($stubName): string
    {
        return file_get_contents(__DIR__ . "/sitemapStubs/{$stubName}.xml");
    }

    protected function sanitizeHtmlWhitespace(string $subject) : string
    {
        $find = ['/>\s+</', '/(^\s+)|(\s+$)/'];
        $replace = ['><', ''];

        return preg_replace($find, $replace, $subject);
    }

}
