<?php

namespace Spatie\Sitemap\Tags;

class Image extends Tag
{
    /** @var string */
    public $url = '';

    /** @var string */
    public $caption;

    /** @var string */
    public $title;

    /** @var string */
    public $geoLocation;

    /** @var string */
    public $license;

    /**
     * @param string $url
     * @return self
     */
    public static function create(string $url): self
    {
        return new static($url);
    }

    /**
     * @param string $url
     */
    public function __construct(string $url)
    {
        $this->url = $url;
    }

    /**
     * @param string $url
     * @return self
     */
    public function setUrl(string $url): self
    {
        $this->url = $url;

        return $this;
    }

    /**
     * @param string $caption
     * @return self
     */
    public function setCaption(string $caption): self
    {
        $this->caption = $caption;

        return $this;
    }

    /**
     * @param string $title
     * @return self
     */
    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @param string $geoLocation
     * @return self
     */
    public function setGeoLocation(string $geoLocation): self
    {
        $this->geoLocation = $geoLocation;

        return $this;
    }

    /**
     * @param string $license
     * @return self
     */
    public function setLicense(string $license): self
    {
        $this->license = $license;

        return $this;
    }
}
