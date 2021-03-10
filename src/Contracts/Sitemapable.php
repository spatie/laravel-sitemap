<?php

namespace Spatie\Sitemap\Contracts;

use Spatie\Sitemap\Tags\Url;

interface Sitemapable
{
    public function toSitemapTag(): Url | string | array;
}
