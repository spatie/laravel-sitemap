<?php

namespace Spatie\Sitemap\Tags;

class Video
{
    public const PLATFORM_WEB    = 'web';
    public const PLATFORM_MOBILE = 'mobile';
    public const PLATFORM_TV     = 'tv';

    public string $thumbnailLoc;

    public string $title;

    public string $description;

    public string $contentLoc;

    public string $playerLoc;

    public ?array $platforms;

    public function __construct(string $thumbnailLoc, string $title, string $description, string $contentLoc = null, string $playerLoc = null, ?array $platforms = null)
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
            ->setPlatforms($platforms);
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

    public function setContentLoc(string $contentLoc): self
    {
        $this->contentLoc = $contentLoc;

        return $this;
    }

    public function setPlayerLoc(string $playerLoc): self
    {
        $this->playerLoc = $playerLoc;

        return $this;
    }

    public function setPlatforms(?array $platforms): self
    {
        $this->platforms = $platforms;

        return $this;
    }
}
