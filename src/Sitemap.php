<?php

namespace Spatie\Sitemap;

class Sitemap
{
    /** @var array */
    protected $tags = [];

    public function add($tag)
    {
        $this->tags[] = $tag;
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
