<?php

namespace App\Policies;

use App\Models\CarBooking;
use App\Models\User;

class CarBookingPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, CarBooking $carBooking): bool
    {
        return $user->id === $carBooking->user_id || in_array($user->role ?? '', ['admin', 'manager']);
    }

    public function delete(User $user, CarBooking $carBooking): bool
    {
        return $user->id === $carBooking->user_id || in_array($user->role ?? '', ['admin', 'manager']);
    }
}
