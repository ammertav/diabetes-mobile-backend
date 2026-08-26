<?php

namespace App\DTO;

class FastingLogWebFilterData
{
    public function __construct(
        public ?string $search,
        public ?string $status,
        public ?string $date,
        public int $page
    ) {}

    public static function fromRequest(array $validated, int $page = 1): self
    {
        return new self(
            search: $validated['search'] ?? null,
            status: $validated['status'] ?? null,
            date: $validated['date'] ?? null,
            page: $page
        );
    }
}
