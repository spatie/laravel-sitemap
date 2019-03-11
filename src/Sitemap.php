<?php

namespace Spatie\Sitemap;

use Spatie\Sitemap\Tags\Tag;
use Spatie\Sitemap\Tags\Url;

class Sitemap
{
    /**
     * @var array
     */
    protected $tags = [];

    /**
     * @var bool
     */
    protected $formatDocument = false;

    /**
     * @return self
     */
    public static function create(): self
    {
        return new static();
    }

    /**
     * @param string|\Spatie\Sitemap\Tags\Tag $tag
     *
     * @return self
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

    /**
     * @return array
     */
    public function getTags(): array
    {
        return $this->tags;
    }

    /**
     * @param string $url
     *
     * @return self
     */
    public function getUrl(string $url): ?Url
    {
        return collect($this->tags)->first(function (Tag $tag) use ($url) {
            return $tag->getType() === 'url' && $tag->url === $url;
        });
    }

    /**
     * @param string $url
     *
     * @return bool
     */
    public function hasUrl(string $url): bool
    {
        return (bool) $this->getUrl($url);
    }

    /**
     * @return string
     * @throws \Throwable
     */
    public function render(): string
    {
        sort($this->tags);

        $tags = $this->tags;

        $header = view('laravel-sitemap::header')->render();
        $document = view('laravel-sitemap::sitemap')
                ->with(compact('tags'))
                ->render();

        if ($this->formatDocument) {
            $document = self::formatDocument($document);
        }

        return $header.$document;
    }

    /**
     * @param string $path
     *
     * @return self
     * @throws \Throwable
     */
    public function writeToFile(string $path): self
    {
        file_put_contents($path, $this->render());

        return $this;
    }

    /**
     * @param bool $formatDocument
     *
     * @return self
     */
    public function setFormatDocument(bool $formatDocument = true)
    {
        $this->formatDocument = $formatDocument;

        return $this;
    }

    /**
     * @param string $xml
     *
     * @return string
     */
    protected static function formatDocument(string $xml): string
    {
        if (! extension_loaded('dom')) {
            return $xml;
        }

        $dom = new \DOMDocument();
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = true;

        $dom->loadXML($xml);

        return $dom->saveXML($dom->documentElement).PHP_EOL;
    }
}
