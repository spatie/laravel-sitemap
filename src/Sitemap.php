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

    public static function createFromFile(string $path)
    {
        $sitemap = new static();

        if (! file_exists($path)) {
            return $sitemap;
        }

        $tags = json_decode(json_encode(simplexml_load_string(file_get_contents($path))), true);

        collect($tags)->each(function ($tag) use ($sitemap) {
            $url = new Url($tag['loc']);
            $url->setPriority($tag['priority']);
            $url->setChangeFrequency($tag['changefreq']);
            $url->setLastModificationDate(new \DateTime($tag['lastmod']));

            $sitemap->add($url);
        });

        return $sitemap;
    }

    /**
     * @param string|\Spatie\Sitemap\Tags\Tag $tag
     *
     * @return $this
     */
    public function add($tag)
    {
        if (is_string($tag)) {
            $tag = Url::create($tag);
        }

        if (! in_array($tag, $this->tags)) {
            $this->tags[] = $tag;
        }

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
