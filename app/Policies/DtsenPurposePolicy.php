<?php

namespace App\Policies;

use App\Models\DtsenPurpose;
use App\Models\User;

class DtsenPurposePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isAdmin() || $user->isPetugasDinsos();
    }

    public function view(User $user, DtsenPurpose $purpose): bool
    {
        return $user->isAdmin() || $user->isPetugasDinsos();
    }

    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    public function update(User $user, DtsenPurpose $purpose): bool
    {
        return $user->isAdmin();
    }

    public function delete(User $user, DtsenPurpose $purpose): bool
    {
        return $user->isAdmin();
    }

    public function deleteAny(User $user): bool
    {
        return $user->isAdmin();
    }
}
