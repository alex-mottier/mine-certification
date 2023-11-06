<?php

namespace App\Domain\Chapter\Model;

readonly class SearchChapter
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
