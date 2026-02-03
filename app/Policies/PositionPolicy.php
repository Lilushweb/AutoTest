<?php

namespace App\Policies;

use App\Models\Position;
use App\Models\User;

class PositionPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return in_array($user->role ?? '', ['admin', 'manager']);
    }

    public function update(User $user, Position $position): bool
    {
        return in_array($user->role ?? '', ['admin', 'manager']);
    }

    public function delete(User $user, Position $position): bool
    {
        return in_array($user->role ?? '', ['admin', 'manager']);
    }
}
