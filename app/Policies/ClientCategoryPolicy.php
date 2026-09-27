<?php

namespace App\Policies;

use App\Models\ClientCategory;
use App\Models\User;

class ClientCategoryPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isAdmin() || $user->isPetugasDinsos();
    }

    public function view(User $user, ClientCategory $category): bool
    {
        return $user->isAdmin() || $user->isPetugasDinsos();
    }

    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    public function update(User $user, ClientCategory $category): bool
    {
        return $user->isAdmin();
    }

    public function delete(User $user, ClientCategory $category): bool
    {
        return $user->isAdmin();
    }

    public function deleteAny(User $user): bool
    {
        return $user->isAdmin();
    }
}
