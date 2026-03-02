<?php

namespace Spatie\Sitemap;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Spatie\Sitemap\Tags\Sitemap;
use Spatie\Sitemap\Tags\Tag;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

class SitemapIndex implements Renderable, Responsable
{
    /** @var Sitemap[] */
    protected array $tags = [];

    protected ?string $stylesheetUrl = null;

    public static function create(): static
    {
        return new static;
    }

    public function setStylesheet(string $url): static
    {
        $this->stylesheetUrl = $url;

        return $this;
    }

    public function add(string|Sitemap $tag): static
    {
        if (is_string($tag)) {
            $tag = Sitemap::create($tag);
        }

        $this->tags[] = $tag;

        return $this;
    }

    public function getSitemap(string $url): ?Sitemap
    {
        return collect($this->tags)->first(function (Tag $tag) use ($url) {
            return $tag->getType() === 'sitemap' && $tag->url === $url;
        });
    }

    public function hasSitemap(string $url): bool
    {
        return (bool) $this->getSitemap($url);
    }

    public function render(): string
    {
        $tags = $this->tags;
        $stylesheetUrl = $this->stylesheetUrl;

        return view('sitemap::sitemapIndex/index')
            ->with(compact('tags', 'stylesheetUrl'))
            ->render();
    }

    public function writeToFile(string $path): static
    {
        file_put_contents($path, $this->render());

        return $this;
    }

    public function writeToDisk(string $disk, string $path, bool $public = false): static
    {
        $visibility = $public ? 'public' : 'private';

        Storage::disk($disk)->put($path, $this->render(), $visibility);

        return $this;
    }

    public function toResponse($request): SymfonyResponse
    {
        return Response::make($this->render(), 200, [
            'Content-Type' => 'text/xml',
        ]);
    }
}
