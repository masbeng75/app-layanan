<?php

namespace App\Policies;

use App\Models\InformationPage;
use App\Models\User;

class InformationPagePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isAdmin() || $user->isPetugasDinsos();
    }

    public function view(User $user, InformationPage $page): bool
    {
        return $user->isAdmin() || $user->isPetugasDinsos();
    }

    public function create(User $user): bool
    {
        return $user->isAdmin() || $user->isPetugasDinsos();
    }

    public function update(User $user, InformationPage $page): bool
    {
        return $user->isAdmin() || $user->isPetugasDinsos();
    }

    public function delete(User $user, InformationPage $page): bool
    {
        return $user->isAdmin();
    }

    public function deleteAny(User $user): bool
    {
        return $user->isAdmin();
    }
}
