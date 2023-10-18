<?php

namespace Spatie\Sitemap\Tags;

class Video
{
    public const OPTION_PLATFORM_WEB = 'web';
    public const OPTION_PLATFORM_MOBILE = 'mobile';
    public const OPTION_PLATFORM_TV = 'tv';

    public const OPTION_NO = "no";
    public const OPTION_YES = "yes";

    public string $thumbnailLoc;

    public string $title;

    public string $description;

    public ?string $contentLoc;

    public ?string $playerLoc;

    public array $options;

    public array $allow;

    public array $deny;

    public array $tags;

    public function __construct(string $thumbnailLoc, string $title, string $description, string $contentLoc = null, string|array $playerLoc = null, array $options = [], array $allow = [], array $deny = [], array $tags = [])
    {
        if ($contentLoc === null && $playerLoc === null) {
            // https://developers.google.com/search/docs/crawling-indexing/sitemaps/video-sitemaps
            throw new \Exception("It's required to provide either a Content Location or Player Location");
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

    public function setThumbnailLoc(string $thumbnailLoc): self
    {
        $this->thumbnailLoc = $thumbnailLoc;

        return $this;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function setContentLoc(?string $contentLoc): self
    {
        $this->contentLoc = $contentLoc;

        return $this;
    }

    public function setPlayerLoc(?string $playerLoc): self
    {
        $this->playerLoc = $playerLoc;

        return $this;
    }

    public function setOptions(?array $options): self
    {
        $this->options = $options;

        return $this;
    }

    public function setAllow(array $allow): self
    {
        $this->allow = $allow;

        return $this;
    }

    public function setDeny(array $deny): self
    {
        $this->deny = $deny;

        return $this;
    }

    public function setTags(array $tags): self
    {
        $this->tags = array_slice($tags, 0, 32); // maximum 32 tags allowed

        return $this;
    }
}
