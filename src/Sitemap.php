<?php

namespace Spatie\Sitemap;

use Illuminate\Contracts\Support\Responsable;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Spatie\Sitemap\Tags\Tag;
use Spatie\Sitemap\Tags\Url;

class Sitemap implements Responsable
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

    public function render(): string
    {
        sort($this->tags);

        $tags = collect($this->tags)->unique('url')->filter();

        return $this->render_view($tags);
    }

    /**
     * Replacement for resources/views/sitemap.blade.php
     * @param $tags
     * @return string
     */
    public function render_view($tags): string
    {
        $render =  '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL;
        $render .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xhtml="http://www.w3.org/1999/xhtml">' . PHP_EOL;
        foreach ($tags as $tag)
            $render .= $this->render_tag($tag->getType(),$tag);
        $render .= "</urlset>";
        return $render;
    }

    /**
     * Replacement for resources/views/url.blade.php
     * @param $type
     * @param $tag
     * @return string
     */
    public function render_tag($type, $tag): string
    {
        switch ($type)
        {
            case 'url':
                $render = "  <url>";
                if (!empty($tag->url))
                    $render .= "    <loc>" . url($tag->url) . "</loc>" . PHP_EOL;
                if (count($tag->alternates))
                    foreach ($tag->alternates as $alternate)
                        $render .= "<xhtml:link rel='alternate' hreflang='{$alternate->locale}' href='" . url($alternate->url) . "' />" . PHP_EOL;
                if (!empty($tag->lastModificationDate))
                    $render .= "    <lastmod>" . $tag->lastModificationDate->format(\DateTimeInterface::ATOM) . "</lastmod>" . PHP_EOL;
                if (!empty($tag->changeFrequency))
                    $render .= "    <changefreq>{$tag->changeFrequency}</changefreq>" . PHP_EOL;
                if (!empty($tag->priority))
                    $render .= "    <priority>" . number_format($tag->priority, 1) . "</priority>" . PHP_EOL;
                $render .= "  </url>";
                return $render;
            default:
                return view("laravel-sitemap::$type", $tag);
        }
    }

    public function writeToFile(string $path): self
    {
        file_put_contents($path, $this->render());

        return $this;
    }

    public function writeToDisk(string $disk, string $path): self
    {
        Storage::disk($disk)->put($path, $this->render());

        return $this;
    }

    /**
     * Create an HTTP response that represents the object.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function toResponse($request)
    {
        return Response::make($this->render(), 200, [
            'Content-Type' => 'text/xml',
        ]);
    }
}
