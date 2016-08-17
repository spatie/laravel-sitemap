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

    /** @var string */
    public $url = '';

    /** @var \Carbon\Carbon */
    public $lastModificationDate;

    /** @var string */
    public $changeFrequency;

    /** @var float */
    public $priority = 0.8;

    public static function create(string $url): Url
    {
        return new static($url);
    }

    public function __construct(string $url)
    {
        $this->url = $url;

        $this->lastModificationDate = Carbon::now();

        $this->changeFrequency = static::CHANGE_FREQUENCY_DAILY;
    }

    /**
     * @param string $url
     *
     * @return $this
     */
    public function url(string $url = '')
    {
        $this->url = $url;

        return $this;
    }

    /**
     * @param \Carbon\Carbon $lastModificationDate
     *
     * @return $this
     */
    public function lastModificationDate(Carbon $lastModificationDate)
    {
        $this->lastModificationDate = $lastModificationDate;

        return $this;
    }

    /**
     * @param string $changeFrequency
     *
     * @return $this
     */
    public function changeFrequency(string $changeFrequency)
    {
        $this->changeFrequency = $changeFrequency;

        return $this;
    }

    /**
     * @param float $priority
     *
     * @return $this
     */
    public function priority(float $priority)
    {
        $this->priority = $priority;

        return $this;
    }
}
