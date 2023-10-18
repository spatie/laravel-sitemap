<?php

namespace Spatie\Sitemap\Tags;

use Carbon\Carbon;
use DateTimeInterface;

class News
{
    public const OPTION_ACCESS_SUB = 'Subscription';
    public const OPTION_ACCESS_REG = 'Registration';

    public const OPTION_GENRES_PR = 'PressRelease';
    public const OPTION_GENRES_SATIRE = 'Satire';
    public const OPTION_GENRES_BLOG = 'Blog';
    public const OPTION_GENRES_OPED = 'OpEd';
    public const OPTION_GENRES_OPINION = 'Opinion';
    public const OPTION_GENRES_UG = 'UserGenerated';

    public string $name;

    public string $language;

    public string $title;

    public Carbon $publicationDate;

    public ?array $options;

    public function __construct(
        string $name,
        string $language,
        string $title,
        DateTimeInterface $publicationDate,
        array $options = []
    ) {
        $this
            ->setName($name)
            ->setLanguage($language)
            ->setTitle($title)
            ->setPublicationDate($publicationDate)
            ->setOptions($options);
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function setLanguage(string $language): self
    {
        $this->language = $language;

        return $this;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function setPublicationDate(DateTimeInterface $publicationDate): self
    {
        $this->publicationDate = Carbon::instance($publicationDate);

        return $this;
    }

    public function setOptions(array $options): self
    {
        $this->options = $options;

        return $this;
    }
}
