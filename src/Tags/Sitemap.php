<?php

namespace Spatie\Sitemap\Tags;

use Carbon\Carbon;
use DateTime;

class Sitemap extends Tag
{
    /** @var string */
    public $url = '';

    /** @var \Carbon\Carbon */
    public $lastModificationDate;

    public static function create(string $url): self
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
}
