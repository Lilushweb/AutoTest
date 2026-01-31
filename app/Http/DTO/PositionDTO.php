<?php

namespace App\Http\DTO;

class PositionDTO
{
    public function __construct(
        public string $name,
        public array $comfortCategoryIds
    ) {}
}
