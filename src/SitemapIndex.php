<?php

namespace Spatie\Sitemap;

use Illuminate\Contracts\Support\Responsable;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Spatie\Sitemap\Tags\Sitemap;
use Spatie\Sitemap\Tags\Tag;

class SitemapIndex implements Responsable
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
        $tags = $this->tags;

        return view('laravel-sitemap::sitemapIndex/index')
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
