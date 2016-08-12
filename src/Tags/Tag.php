<?php

namespace Spatie\Sitemap\Tags;


abstract class Tag {

    public function getType(): string
{
    return class_basename(static::class);
}


}
