<?php

namespace Spatie\Sitemap;

use Spatie\Sitemap\Tags\Tag;
use Spatie\Sitemap\Tags\Url;

class Sitemap
{
    /** @var array */
    protected $tags = [];

    /** @var string */
    protected $sitemapXsl;

    public static function create(): self
    {
        return new static(config('sitemap.sitemap_xsl'));
    }

    public function __construct(string $sitemapXsl)
    {
        $this->sitemapXsl = $sitemapXsl;
        return $this;
    }

    /**
     * @param string|\Spatie\Sitemap\Tags\Tag $tag
     *
     * @return $this
     */
    public function add($tag): self
    {
        if (is_string($tag)) {
            $tag = Url::create($tag);
        }

        if (! in_array($tag, $this->tags)) {
            $this->tags[] = $tag;
        }

        return $this;
    }

    public function getTags(): array
    {
        return $this->tags;
    }

    public function getUrl(string $url): ?Url
    {
        return collect($this->tags)->first(function (Tag $tag) use ($url) {
            return $tag->getType() === 'url' && $tag->url === $url;
        });
    }

    public function hasUrl(string $url): bool
    {
        return (bool) $this->getUrl($url);
    }

    public function render(): string
    {
        sort($this->tags);

        $tags = $this->tags;
        $sitemapXsl = $this->sitemapXsl;

        return view('laravel-sitemap::sitemap')
            ->with(compact('tags', 'sitemapXsl'))
            ->render();
    }

    public function writeToFile(string $path): self
    {
        file_put_contents($path, $this->render());

        return $this;
    }
}
