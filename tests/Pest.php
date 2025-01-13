<?php

use Carbon\Carbon;
use function PHPUnit\Framework\assertXmlStringEqualsXmlString;
use Spatie\TemporaryDirectory\TemporaryDirectory;

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
*/

uses(\Spatie\Sitemap\Test\TestCase::class)
    ->beforeEach(function () {
        $this->now = Carbon::create('2016', '1', '1', '0', '0', '0');

        Carbon::setTestNow($this->now);
    })
    ->in('.');

/*
|--------------------------------------------------------------------------
| Expectations
|--------------------------------------------------------------------------
*/

expect()->extend('toEqualXmlString', function (string $expected_xml) {
    /** @var string */
    $value = $this->value;

    assertXmlStringEqualsXmlString($value, $expected_xml);

    return $this;
});

/*
|--------------------------------------------------------------------------
| Functions
|--------------------------------------------------------------------------
*/

function checkIfTestServerIsRunning(): void
{
    try {
        file_get_contents('http://localhost:4020');
    } catch (Throwable $e) {
        handleTestServerNotRunning();
    }
}

function handleTestServerNotRunning(): void
{
    if (getenv('TRAVIS')) {
        test()->fail('The test server is not running on Travis.');
    }

    test()->markTestSkipped('The test server is not running.');
}

function temporaryDirectory(): TemporaryDirectory
{
    return (new TemporaryDirectory())->force()->create();
}
