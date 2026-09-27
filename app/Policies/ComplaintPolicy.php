<?php

namespace App\Policies;

use App\Models\Complaint;
use App\Models\User;

class ComplaintPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isAdmin()
            || $user->isPetugasDinsos()
            || $user->isPejabatPenandatangan()
            || $user->isOperator()
            || $user->isPimpinan();
    }

    public function view(User $user, Complaint $complaint): bool
    {
        if ($user->isAdmin() || $user->isPimpinan() || $user->isPejabatPenandatangan() || $user->isPetugasDinsos()) {
            return true;
        }

        if ($user->isOperator()) {
            return $user->canAccessWilayah($complaint->village?->district_id, $complaint->village_id);
        }

        return $user->id === $complaint->reporter_id;
    }

    public function create(User $user): bool
    {
        return $user->isAdmin() || $user->isPetugasDinsos() || $user->isOperator();
    }

    public function update(User $user, Complaint $complaint): bool
    {
        if ($user->isAdmin() || $user->isPetugasDinsos()) {
            return true;
        }

        if ($user->isOperator()) {
            $statusValue = $complaint->status?->value ?? (string) $complaint->status;

            return $user->canAccessWilayah($complaint->village?->district_id, $complaint->village_id)
                && $statusValue === 'submitted';
        }

        return false;
    }

    public function delete(User $user, Complaint $complaint): bool
    {
        return $user->isAdmin();
    }

    public function deleteAny(User $user): bool
    {
        return $user->isAdmin();
    }
}
