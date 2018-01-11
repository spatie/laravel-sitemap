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
     * @param string|\Spatie\Sitemap\Tags\Tag $tag
     *
     * @return $this
     */
    public function add($tag)
    {
        if (is_string($tag)) {
            $tag = Url::create($tag);
        }

        if (! $this->hasUrl($tag->url)) {
            $this->tags[] = $tag;
        } else {
            $oldTag = $this->getUrl($tag->url);
            if ($tag->isNewer($oldTag)) {
                $this->update($oldTag, $tag);
            }
        }

        return $this;
    }

    /**
     * @param Url $oldTag
     * @param Url $newTag
     *
     * @return $this
     */
    public function update(Url $oldTag, Url $newTag)
    {
        array_splice($this->tags, array_search($oldTag, $this->tags), 1, [$newTag]);

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
