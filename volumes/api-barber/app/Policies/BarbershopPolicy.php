<?php

namespace App\Policies;

use App\Models\Barbershop;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class BarbershopPolicy
{
    /**
     * Determine whether the user can show any models.
     */
    public function showAny(User $user): bool
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can show the model.
     */
    public function show(User $user, Barbershop $barbershop): bool
    {
        return $user->hasRole('admin') ||
            ($user->hasRole('manager') && $barbershop->manager->id === $user->id) ||
            ($user->hasRole('recepcionist') && $barbershop->recepcionist->id === $user->id);
    }

    /**
     * Determine whether the user can create models.
     */
    public function store(User $user): bool
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Barbershop $barbershop): bool
    {
        return $user->hasRole('admin') ||
            ($user->hasRole('manager') && $barbershop->manager->id === $user->id);
    }

    /**
     * Determine whether the user can destroy the model.
     */
    public function destroy(User $user, Barbershop $barbershop): bool
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Barbershop $barbershop): bool
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can permanently destroy the model.
     */
    public function forceDestroy(User $user, Barbershop $barbershop): bool
    {
        return $user->hasRole('admin');
    }
}
