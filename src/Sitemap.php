<?php

namespace Spatie\Sitemap;

use Spatie\Sitemap\Tags\Tag;
use Spatie\Sitemap\Tags\Url;

class Sitemap
{
    /** @var array */
    protected $tags = [];

    /**
     * @return static
     */
    public static function create()
    {
        return new static();
    }

    /**
     * @param string|\Spatie\Sitemap\Tags\Tag $url
     *
     * @return $this
     */
    public function add($url)
    {
        if (is_string($url)) {
            $url = Url::create($url);
        }

        $this->tags[] = $url;

        return $this;
    }

    /**
     * @param string $url
     *
     * @return \Spatie\Sitemap\Tags\Url|null
     */
    public function getUrl(string $url)
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

        return view('laravel-sitemap::sitemap')
            ->with(compact('tags'))
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
