<?php

namespace App\Domain\DTO\Criteria;

readonly class SearchCriteriaDTO
{
    /**
     * @param string|null $name
     * @param string|null $description
     * @param float|null $quota
     * @param int[]|null $chapters
     */
    public function __construct(
        protected ?string $name,
        protected ?string $description,
        protected ?float $quota,
        protected ?array $chapters,
    )
    {}

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

    public function getChapters(): ?array
    {
        return $this->chapters;
    }
}
