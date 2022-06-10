<?php

namespace Spatie\Sitemap\Tags;

class Image
{
    public string $url;

    public string $caption;

    public string $geo_location;

    public string $title;

    public string $license;

    public static function create(string $url, string $caption = '', string $geo_location = '', string $title = '', string $license = ''): static
    {
        return new static($url, $caption, $geo_location, $title, $license);
    }

    public function __construct(string $url, string $caption = '', string $geo_location = '', string $title = '', string $license = '')
    {
        $this->setUrl($url);

        $this->setCaption($caption);

        $this->setGeoLocation($geo_location);

        $this->setTitle($title);

        $this->setLicense($license);
    }

    public function setUrl(string $url = ''): static
    {
        $this->url = $url;

        return $this;
    }

    public function setCaption(string $caption = ''): static
    {
        $this->caption = $caption;

        return $this;
    }

    public function setGeoLocation(string $geo_location = ''): static
    {
        $this->geo_location = $geo_location;

        return $this;
    }

    public function setTitle(string $title = ''): static
    {
        $this->title = $title;

        return $this;
    }

    public function setLicense(string $license = ''): static
    {
        $this->license = $license;

        return $this;
    }
}
