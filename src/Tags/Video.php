<?php

namespace Spatie\Sitemap\Tags;

use InvalidArgumentException;

class Video
{
    public const OPTION_PLATFORM_WEB = 'web';

    public const OPTION_PLATFORM_MOBILE = 'mobile';

    public const OPTION_PLATFORM_TV = 'tv';

    public const OPTION_NO = 'no';

    public const OPTION_YES = 'yes';

    public string $thumbnailLoc;

    public string $title;

    public string $description;

    public ?string $contentLoc;

    public ?string $playerLoc;

    public array $options;

    public array $allow;

    public array $deny;

    public array $tags;

    public function __construct(string $thumbnailLoc, string $title, string $description, ?string $contentLoc = null, ?string $playerLoc = null, array $options = [], array $allow = [], array $deny = [], array $tags = [])
    {
        if ($contentLoc === null && $playerLoc === null) {
            throw new InvalidArgumentException("It's required to provide either a Content Location or Player Location");
        }

        $this->setThumbnailLoc($thumbnailLoc)
            ->setTitle($title)
            ->setDescription($description)
            ->setContentLoc($contentLoc)
            ->setPlayerLoc($playerLoc)
            ->setOptions($options)
            ->setAllow($allow)
            ->setDeny($deny)
            ->setTags($tags);
    }

    public function setThumbnailLoc(string $thumbnailLoc): static
    {
        $this->thumbnailLoc = $thumbnailLoc;

        return $this;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function setContentLoc(?string $contentLoc): static
    {
        $this->contentLoc = $contentLoc;

        return $this;
    }

    public function setPlayerLoc(?string $playerLoc): static
    {
        $this->playerLoc = $playerLoc;

        return $this;
    }

    public function setOptions(array $options): static
    {
        $this->options = $options;

        return $this;
    }

    public function setAllow(array $allow): static
    {
        $this->allow = $allow;

        return $this;
    }

    public function setDeny(array $deny): static
    {
        $this->deny = $deny;

        return $this;
    }

    public function setTags(array $tags): static
    {
        $this->tags = array_slice($tags, 0, 32); // maximum 32 tags allowed

        return $this;
    }
}
