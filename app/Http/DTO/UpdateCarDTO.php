<?php

namespace App\Http\DTO;

class UpdateCarDTO
{
    public function __construct(
        public ?string $model = null,
        public ?int $comfortCategoryId = null,
    ) {}
}
