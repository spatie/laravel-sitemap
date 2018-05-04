<?php

namespace Spatie\Sitemap;

use Spatie\Sitemap\Tags\Tag;
use Spatie\Sitemap\Tags\Sitemap;

class SitemapIndex
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
    public function add($tag)
    {
        if (is_string($tag)) {
            $tag = Sitemap::create($tag);
        }

        $this->tags[] = $tag;

        return $this;
    }

    /**
     * Get sitemap tag.
     *
     * @param string $url
     *
     * @return \Spatie\Sitemap\Tags\Sitemap|null
     */
    public function getSitemap(string $url)
    {
        return collect($this->tags)->first(function (Tag $tag) use ($url) {
            return $tag->getType() === 'sitemap' && $tag->url === $url;
        });
    }

    /**
     * Check if there is the provided sitemap in the index.
     *
     * @param string $url
     *
     * @return bool
     */
    public function hasSitemap(string $url): bool
    {
        return (bool) $this->getSitemap($url);
    }

    /**
     * Get the inflated template content.
     *
     * @return string
     */
    public function render(): string
    {
        $sitemapXsl = $this->sitemapXsl;
        $tags = $this->tags;

        return view('laravel-sitemap::sitemapIndex/index')
            ->with(compact('tags', 'sitemapXsl'))
            ->render();
    }

    /**
     * @param string $path
     *
     * @return $this
     */
    public function writeToFile(string $path)
    {
        file_put_contents($path, $this->render());

        return $this;
    }
}
