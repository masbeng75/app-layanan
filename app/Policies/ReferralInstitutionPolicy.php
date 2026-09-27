<?php

namespace App\Policies;

use App\Models\ReferralInstitution;
use App\Models\User;

class ReferralInstitutionPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isAdmin() || $user->isPetugasDinsos();
    }

    public function view(User $user, ReferralInstitution $institution): bool
    {
        return $user->isAdmin() || $user->isPetugasDinsos();
    }

    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    public function update(User $user, ReferralInstitution $institution): bool
    {
        return $user->isAdmin();
    }

    public function delete(User $user, ReferralInstitution $institution): bool
    {
        return $user->isAdmin();
    }

    public function deleteAny(User $user): bool
    {
        return $user->isAdmin();
    }
}
