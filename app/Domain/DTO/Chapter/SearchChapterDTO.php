<?php

namespace App\Domain\DTO\Chapter;

readonly class SearchChapterDTO
{
    public function __construct(
        protected ?string $name,
        protected ?string $description,
        protected ?float  $quota,
    )
    {
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getQuota(): ?float
    {
        return $this->quota;
    }
}
