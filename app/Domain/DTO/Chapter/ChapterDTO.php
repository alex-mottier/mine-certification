<?php

namespace App\Domain\DTO\Chapter;

use JsonSerializable;

readonly class ChapterDTO implements JsonSerializable
{
    /**
     * @param int $id
     * @param string $name
     * @param string $description
     * @param float $quota
     * @param array $criterias
     */
    public function __construct(
        protected int $id,
        protected string $name,
        protected string $description,
        protected float  $quota,
        protected array $criterias
    )
    {
    }

    public function jsonSerialize(): array
    {
        return array_filter([
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'quota' => $this->quota,
            'criterias' => $this->criterias
        ]);
    }
}
