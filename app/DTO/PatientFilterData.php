<?php

namespace App\DTO;

class PatientFilterData
{
    public function __construct(
        public ?string $search,
        public ?string $risk,
        public ?string $protocol,
        public ?string $date,
        public int $page
    ) {}

    public static function fromRequest(array $validated, int $page = 1): self
    {
        return new self(
            search: $validated['search'] ?? null,
            risk: $validated['risk'] ?? null,
            protocol: $validated['protocol'] ?? null,
            date: $validated['date'] ?? null,
            page: $page
        );
    }
}
