<?php

namespace Spatie\Sitemap\Tags;

use Illuminate\Support\Str;

abstract class Tag
{
    public function getType(): string
    {
        return Str::of(static::class)->classBasename()->lower();
    }
}
