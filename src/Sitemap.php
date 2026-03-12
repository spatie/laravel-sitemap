<?php

namespace Spatie\Sitemap;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Spatie\Sitemap\Contracts\Sitemapable;
use Spatie\Sitemap\Tags\Tag;
use Spatie\Sitemap\Tags\Url;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

class Sitemap implements Renderable, Responsable
{
    /** @var Url[] */
    protected array $tags = [];

    protected int $maximumTagsPerSitemap = 0;

    protected ?string $stylesheetUrl = null;

    public static function create(): static
    {
        return new static;
    }

    public function maxTagsPerSitemap(int $maximumTagsPerSitemap = 50000): static
    {
        $this->maximumTagsPerSitemap = $maximumTagsPerSitemap;

        return $this;
    }

    public function setStylesheet(string $url): static
    {
        $this->stylesheetUrl = $url;

        return $this;
    }

    public function add(string|Url|Sitemapable|iterable $tag): static
    {
        if (is_object($tag) && array_key_exists(Sitemapable::class, class_implements($tag))) {
            $tag = $tag->toSitemapTag();
        }

        if (is_iterable($tag)) {
            foreach ($tag as $item) {
                $this->add($item);
            }

            return $this;
        }

        if (is_string($tag) && trim($tag) === '') {
            return $this;
        }

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
        $tags = collect($this->tags)->unique('url')->filter();
        $stylesheetUrl = $this->stylesheetUrl;

        return view('sitemap::sitemap')
            ->with(compact('tags', 'stylesheetUrl'))
            ->render();
    }

    public function writeToFile(string $path): static
    {
        if (! $this->shouldSplit()) {
            file_put_contents($path, $this->render());

            return $this;
        }

        foreach ($this->buildSplitSitemaps($path, basename($path)) as $filePath => $xml) {
            file_put_contents($filePath, $xml);
        }

        return $this;
    }

    public function writeToDisk(string $disk, string $path, bool $public = false): static
    {
        $visibility = $public ? 'public' : 'private';

        if (! $this->shouldSplit()) {
            Storage::disk($disk)->put($path, $this->render(), $visibility);

            return $this;
        }

        foreach ($this->buildSplitSitemaps($path) as $filePath => $xml) {
            Storage::disk($disk)->put($filePath, $xml, $visibility);
        }

        return $this;
    }

    /**
     * @return array<string, string> Map of file paths to rendered XML content.
     *                               The index sitemap is keyed by the original path.
     */
    protected function buildSplitSitemaps(string $path, ?string $urlPath = null): array
    {
        $urlPath ??= $path;

        $index = new SitemapIndex;

        if ($this->stylesheetUrl) {
            $index->setStylesheet($this->stylesheetUrl);
        }

        $fileFormat = str_replace('.xml', '_%d.xml', $path);
        $urlFormat = str_replace('.xml', '_%d.xml', $urlPath);
        $files = [];

        foreach ($this->chunkTags() as $key => $chunk) {
            $chunkSitemap = Sitemap::create();

            if ($this->stylesheetUrl) {
                $chunkSitemap->setStylesheet($this->stylesheetUrl);
            }

            foreach ($chunk as $tag) {
                $chunkSitemap->add($tag);
            }

            $chunkFilePath = sprintf($fileFormat, $key);
            $files[$chunkFilePath] = $chunkSitemap->render();
            $index->add(sprintf($urlFormat, $key));
        }

        $files[$path] = $index->render();

        return $files;
    }

    protected function shouldSplit(): bool
    {
        return $this->maximumTagsPerSitemap > 0
            && count($this->tags) > $this->maximumTagsPerSitemap;
    }

    protected function chunkTags(): array
    {
        return collect($this->tags)
            ->unique('url')
            ->filter()
            ->chunk($this->maximumTagsPerSitemap)
            ->toArray();
    }

    public function toResponse($request): SymfonyResponse
    {
        return Response::make($this->render(), 200, [
            'Content-Type' => 'text/xml',
        ]);
    }

    public function sort(): static
    {
        sort($this->tags);

        return $this;
    }
}
