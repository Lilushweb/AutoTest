<?php

namespace App\Http\DTO;

use Carbon\Carbon;

class UpdateCarBookingDTO
{
    public function __construct(
        public ?Carbon $startTime = null,
        public ?Carbon $endTime = null,
    ) {}
}
