<?php

namespace Spatie\Sitemap\Tags;

use DateTime;
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

    /** @var array */
    public $alternates = [];

    public static function create(string $url): self
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
    public function setUrl(string $url = '')
    {
        $this->url = $url;

        return $this;
    }

    /**
     * @param \DateTime $lastModificationDate
     *
     * @return $this
     */
    public function setLastModificationDate(DateTime $lastModificationDate)
    {
        $this->lastModificationDate = $lastModificationDate;

        return $this;
    }

    /**
     * @param string $changeFrequency
     *
     * @return $this
     */
    public function setChangeFrequency(string $changeFrequency)
    {
        $this->changeFrequency = $changeFrequency;

        return $this;
    }

    /**
     * @param float $priority
     *
     * @return $this
     */
    public function setPriority(float $priority)
    {
        $this->priority = $priority;

        return $this;
    }

    /**
     * @param Alternate $alternate
     *
     * @param string $url
     * @param string $locale
     * @return $this
     */
    public function addAlternate(string $url, string $locale = '')
    {
        $this->alternates[] = new Alternate($url, $locale);

        return $this;
    }

    /**
     * @return string
     */
    public function path(): string
    {
        return parse_url($this->url)['path'] ?? '';
    }

    /**
     * @param int|null $index
     *
     * @return array|null|string
     */
    public function segments(int $index = null)
    {
        $segments = collect(explode('/', $this->path()))
            ->filter(function ($value) {
                return $value !== '';
            })
            ->values()
            ->toArray();

        if (! is_null($index)) {
            return $this->segment($index);
        }

        return $segments;
    }

    /**
     * @param int $index
     *
     * @return string|null
     */
    public function segment(int $index)
    {
        return $this->segments()[$index - 1] ?? null;
    }
}
