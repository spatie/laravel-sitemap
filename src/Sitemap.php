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

        $tags = collect($this->tags)->unique('url');

        return view('laravel-sitemap::sitemap')
            ->with(compact('tags'))
            ->render();
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
