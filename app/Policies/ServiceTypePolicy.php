<?php

namespace App\Policies;

use App\Models\ServiceType;
use App\Models\User;

class ServiceTypePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isAdmin() || $user->isPetugasDinsos();
    }

    public function view(User $user, ServiceType $serviceType): bool
    {
        return $user->isAdmin() || $user->isPetugasDinsos();
    }

    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    public function update(User $user, ServiceType $serviceType): bool
    {
        return $user->isAdmin();
    }

    public function delete(User $user, ServiceType $serviceType): bool
    {
        return $user->isAdmin();
    }

    public function deleteAny(User $user): bool
    {
        return $user->isAdmin();
    }
}
