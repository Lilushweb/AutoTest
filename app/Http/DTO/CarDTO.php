<?php

namespace App\Http\DTO;

class CarDTO
{
    public function __construct(
        public string $model,
        public int $comfortCategoryId,
    ) {}
}
