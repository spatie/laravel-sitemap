<?php

namespace Spatie\Sitemap;

use Carbon\Carbon;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use SimpleXMLElement;
use Spatie\Sitemap\Contracts\Sitemapable;
use Spatie\Sitemap\Tags\Tag;
use Spatie\Sitemap\Tags\Url;

class Sitemap implements Responsable, Renderable
{
    /** @var \Spatie\Sitemap\Tags\Url[] */
    protected array $tags = [];

    public static function create(): static
    {
        return new static();
    }

    public static function load(string $path) : static
    {
        $map = new static();

        $data = simplexml_load_string(str_replace('xhtml:link', 'xhtml_link', file_get_contents($path)));

        foreach ($data as $item)
        {
            $url = Url::create($item->loc)
                ->setLastModificationDate(new Carbon($item->lastmod))
                ->setChangeFrequency($item->changefreq)
                ->setPriority(floatval($item->priority));

            foreach ($item->xhtml_link as $link)
                $url->addAlternate($link->attributes()->href, $link->attributes()->hreflang);

            /** @var SimpleXMLElement $item */
            $map->add($url);
        }

        return $map;
    }

    public function add(string | Url | Sitemapable | iterable $tag): static
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

        return view('sitemap::sitemap')
            ->with(compact('tags'))
            ->render();
    }

    public function writeToFile(string $path): static
    {
        file_put_contents($path, $this->render());

        return $this;
    }

    public function writeToDisk(string $disk, string $path): static
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
