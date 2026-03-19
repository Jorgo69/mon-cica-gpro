<?php

namespace App\DTOs;

class SubActivityDTO
{
    public function __construct(
        public string $id,
        public string $status,
    ) {}

    public static function fromArray(array $data): self
    {

        return new self(
            id: $data['id'] ?? '',
            status: $data['status'] ?? '',
        );
    }
}
