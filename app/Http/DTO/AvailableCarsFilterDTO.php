<?php

namespace App\Http\DTO;

use Carbon\Carbon;

class AvailableCarsFilterDTO
{
    public function __construct(
        public Carbon $startTime,
        public Carbon $endTime,
        public ?int $userId = null,
        public ?string $search = null,
        public ?array $comfortCategoryIds = null,
    ) {}
}
