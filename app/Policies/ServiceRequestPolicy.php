<?php

namespace App\Policies;

use App\Models\ServiceRequest;
use App\Models\User;

class ServiceRequestPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isAdmin()
            || $user->isPetugasDinsos()
            || $user->isPejabatPenandatangan()
            || $user->isOperator()
            || $user->isPimpinan();
    }

    public function view(User $user, ServiceRequest $request): bool
    {
        if ($user->isAdmin() || $user->isPimpinan() || $user->isPejabatPenandatangan() || $user->isPetugasDinsos()) {
            return true;
        }

        if ($user->isOperator()) {
            return $user->canAccessWilayah($request->village?->district_id, $request->village_id);
        }

        return $user->id === $request->submitter_id;
    }

    public function create(User $user): bool
    {
        return $user->isAdmin() || $user->isPetugasDinsos() || $user->isOperator();
    }

    public function update(User $user, ServiceRequest $request): bool
    {
        if ($user->isAdmin() || $user->isPetugasDinsos()) {
            return true;
        }

        if ($user->isOperator()) {
            $statusValue = $request->status?->value ?? (string) $request->status;

            return $user->canAccessWilayah($request->village?->district_id, $request->village_id)
                && in_array($statusValue, ['draft', 'submitted'], true);
        }

        return false;
    }

    public function delete(User $user, ServiceRequest $request): bool
    {
        return $user->isAdmin();
    }

    public function deleteAny(User $user): bool
    {
        return $user->isAdmin();
    }
}
