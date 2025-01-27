<?php

namespace Spatie\Sitemap\Tags;

class Canonical
{
    public string $url;

    public static function create(string $url): static
    {
        return new static($url);
    }

    public function __construct(string $url)
    {
        $this->setUrl($url);

    }

    public function setUrl(string $url = ''): static
    {
        $this->url = $url;

        return $this;
    }
}