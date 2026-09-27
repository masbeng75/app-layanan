<?php

namespace App\Policies;

use App\Models\RehabilitationCase;
use App\Models\User;

class RehabilitationCasePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isAdmin() || $user->isPimpinan() || $user->isPetugasDinsos();
    }

    public function view(User $user, RehabilitationCase $case): bool
    {
        if ($user->isAdmin() || $user->isPimpinan()) {
            return true;
        }

        if ($user->isPetugasDinsos()) {
            return $case->officer_id === null
                || $case->officer_id === $user->id
                || str_contains($user->workUnit?->name ?? '', 'Rehsos')
                || str_contains($user->workUnit?->name ?? '', 'Rehabilitasi');
        }

        return false;
    }

    public function create(User $user): bool
    {
        return $user->isAdmin() || $user->isPetugasDinsos();
    }

    public function update(User $user, RehabilitationCase $case): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        if ($user->isPetugasDinsos()) {
            return $case->officer_id === null
                || $case->officer_id === $user->id
                || str_contains($user->workUnit?->name ?? '', 'Rehsos')
                || str_contains($user->workUnit?->name ?? '', 'Rehabilitasi');
        }

        return false;
    }

    public function delete(User $user, RehabilitationCase $case): bool
    {
        return $user->isAdmin();
    }

    public function deleteAny(User $user): bool
    {
        return $user->isAdmin();
    }
}
