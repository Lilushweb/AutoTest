<?php

namespace App\Http\DTO;

use Carbon\Carbon;

class CarBookingDTO
{
    public function __construct(
        public int $carId,
        public int $userId,
        public Carbon $startTime,
        public Carbon $endTime,
    ) {}
}
