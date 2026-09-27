<?php

namespace App\Policies;

use App\Models\User;
use App\Models\WorkUnit;

class WorkUnitPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isAdmin();
    }

    public function view(User $user, WorkUnit $model): bool
    {
        return $user->isAdmin();
    }

    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    public function update(User $user, WorkUnit $model): bool
    {
        return $user->isAdmin();
    }

    public function delete(User $user, WorkUnit $model): bool
    {
        return $user->isAdmin();
    }

    public function deleteAny(User $user): bool
    {
        return $user->isAdmin();
    }
}
