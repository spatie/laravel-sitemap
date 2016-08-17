<?php

namespace Spatie\Sitemap;

use Spatie\Sitemap\Tags\Url;

class Sitemap
{
    /** @var array */
    protected $tags = [];

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

        $this->tags[] = $tag;

        return $this;
    }

    public function render(): string
    {
        $tags = $this->tags;

        return view('laravel-sitemap::sitemap')
            ->with(compact('tags'))
            ->render();
    }

    public function writeToFile(string $path)
    {
        file_put_contents($path, $this->render());

        return $this;
    }
}
