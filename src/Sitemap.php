<?php

namespace Spatie\Sitemap;

use Spatie\Sitemap\Tags\Tag;
use Spatie\Sitemap\Tags\Url;

class Sitemap
{
    /** @var array */
    protected $tags = [];

    public static function create(): self
    {
        return new static();
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

    /**
     * @param array $namespaces
     * @return Sitemap
     */
    public function setNamespaces(array $namespaces): self
    {
        SitemapNamespace::overrideNamespaces($namespaces);

        return $this;
    }

    public function render(): string
    {
        sort($this->tags);

        $tags = $this->tags;

        $namespaces = SitemapNamespace::generateNamespaces();

        return view('laravel-sitemap::sitemap')
            ->with(compact('tags', 'namespaces'))
            ->render();
    }

    public function writeToFile(string $path): self
    {
        file_put_contents($path, $this->render());

        SitemapNamespace::setDefault();

        return $this;
    }
}
