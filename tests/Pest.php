<?php

use Carbon\Carbon;
use Spatie\Sitemap\Test\TestCase;
use Spatie\TemporaryDirectory\TemporaryDirectory;

use function PHPUnit\Framework\assertXmlStringEqualsXmlString;

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
*/

uses(TestCase::class)
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

function ensureTestServerIsRunning(): void
{
    if (isTestServerRunning()) {
        return;
    }

    $serverScript = __DIR__.'/server.php';

    $command = sprintf(
        'php -S localhost:4020 %s > /dev/null 2>&1 & echo $!',
        escapeshellarg($serverScript),
    );

    $pid = (int) exec($command);

    file_put_contents(__DIR__.'/.server-pid', (string) $pid);

    $maxAttempts = 50;

    for ($i = 0; $i < $maxAttempts; $i++) {
        if (isTestServerRunning()) {
            return;
        }

        usleep(100_000);
    }

    test()->fail('Could not start the test server.');
}

function isTestServerRunning(): bool
{
    $connection = @fsockopen('localhost', 4020, $errno, $errstr, 1);

    if ($connection) {
        fclose($connection);

        return true;
    }

    return false;
}

function temporaryDirectory(): TemporaryDirectory
{
    return (new TemporaryDirectory)->force()->create();
}

register_shutdown_function(function () {
    $pidFile = __DIR__.'/.server-pid';

    if (file_exists($pidFile)) {
        $pid = (int) file_get_contents($pidFile);

        if ($pid > 0) {
            @exec("kill {$pid} 2>/dev/null");
        }

        @unlink($pidFile);
    }
});
