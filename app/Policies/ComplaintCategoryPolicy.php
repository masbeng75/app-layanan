<?php

namespace App\Policies;

use App\Models\ComplaintCategory;
use App\Models\User;

class ComplaintCategoryPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isAdmin() || $user->isPetugasDinsos();
    }

    public function view(User $user, ComplaintCategory $category): bool
    {
        return $user->isAdmin() || $user->isPetugasDinsos();
    }

    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    public function update(User $user, ComplaintCategory $category): bool
    {
        return $user->isAdmin();
    }

    public function delete(User $user, ComplaintCategory $category): bool
    {
        return $user->isAdmin();
    }

    public function deleteAny(User $user): bool
    {
        return $user->isAdmin();
    }
}
