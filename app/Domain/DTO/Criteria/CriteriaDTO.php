<?php

namespace App\Domain\DTO\Criteria;

use JsonSerializable;

readonly class CriteriaDTO implements JsonSerializable
{
    public function __construct(
        protected int $id,
        protected string $name,
        protected string $description,
        protected float $quota,
        protected int $chapterId
    )
    {}

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'quota' => $this->quota,
            'chapter_id' => $this->chapterId
        ];
    }
}
