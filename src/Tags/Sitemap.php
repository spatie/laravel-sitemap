<?php

namespace Spatie\Sitemap\Tags;

use DateTime;
use Carbon\Carbon;

class Sitemap extends Tag
{
    /** @var string */
    public $url = '';

    /** @var \Carbon\Carbon */
    public $lastModificationDate;

    public static function create(string $url): Sitemap
    {
        return new static($url);
    }

    public function __construct(string $url)
    {
        $this->url = $url;

        $this->lastModificationDate = Carbon::now();
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
