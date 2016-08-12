<?php

namespace Spatie\Sitemap\Tags;

use Carbon\Carbon;

class Url extends Tag
{
    const CHANGE_FREQUENCY_ALWAYS = 'always';
    const CHANGE_FREQUENCY_HOURLY = 'hourly';
    const CHANGE_FREQUENCY_DAILY = 'daily';
    const CHANGE_FREQUENCY_WEEKLY = 'weekly';
    const CHANGE_FREQUENCY_MONTHLY = 'monthly';
    const CHANGE_FREQUENCY_YEARLY = 'yearly';
    const CHANGE_FREQUENCY_NEVER = 'never';

    public $url = '';

    public $lastModificationDate;

    public $changeFrequency;

    public $priority = 0.8;

    public static function create(string $url): Url
    {
        return static ($url);
    }

    public function __construct(string $url)
    {
        $this->url = $url;

        $this->lastModificationDate = Carbon::now();

        $this->changeFrequency = static::CHANGE_FREQUENCY_DAILY;
    }

    public function setLastModificationDate(Carbon $lastModificationDate)
    {
        $this->lastModificationDate = $lastModificationDate;

        return $this;
    }

}
