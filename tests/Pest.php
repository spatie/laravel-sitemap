<?php

use Spatie\TemporaryDirectory\TemporaryDirectory;
use function PHPUnit\Framework\assertXmlStringEqualsXmlString;

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
*/


uses(\Spatie\Sitemap\Test\TestCase::class)->in('.');

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

function temporaryDirectory()
{
    return (new TemporaryDirectory())->force()->create();
}
