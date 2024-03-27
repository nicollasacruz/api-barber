<?php

namespace App\Policies;

use App\Models\Service;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ServicePolicy
{
    /**
     * Determine whether the user can show any models.
     */
    public function showAny(): bool
    {
        return true;
    }

    public function showCollection(): bool
    {
        return true;
    }

    /**
     * Determine whether the user can show the model.
     */
    public function show(): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function store(User $user, Service $service): bool
    {
        return $user->hasRole('admin') ||
            ($user->hasRole('manager') && $service->barbershop->manager->id === $user->id);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Service $service): bool
    {
        return $user->hasRole('admin') ||
            ($user->hasRole('manager') && $service->barbershop->manager->id === $user->id);
    }

    /**
     * Determine whether the user can destroy the model.
     */
    public function destroy(User $user, Service $service): bool
    {
        return $user->hasRole('admin') ||
            ($user->hasRole('manager') && $service->barbershop->manager->id === $user->id);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user): bool
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can permanently destroy the model.
     */
    public function forceDestroy(User $user): bool
    {
        return $user->hasRole('admin');
    }
}
