<?php

namespace App\Policies;

use App\Models\Schedule;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class SchedulePolicy
{
    /**
     * Determine whether the user can show any models.
     */
    public function showAny(User $user): bool
    {
        return $user->hasRole('admin');
    }

    public function showCollection(User $user, Schedule $schedule): bool
    {
        return
            $user->hasRole('admin') ||
            ($user->hasRole('manager') && $schedule->barbershop->manager->id === $user->id) ||
            ($user->hasRole('recepcionist') && $schedule->barbershop->recepcionist->id === $user->id) ||
            $schedule->client->id === $user->id;
    }

    /**
     * Determine whether the user can show the model.
     */
    public function show(User $user, Schedule $schedule): bool
    {
        return $user->hasRole('admin') ||
            ($user->hasRole('manager') && $schedule->barbershop->manager->id === $user->id) ||
            ($user->hasRole('recepcionist') && $schedule->barbershop->recepcionist->id === $user->id) ||
            $schedule->client->id === $user->id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function store(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Schedule $schedule): bool
    {
        return $user->hasRole('admin') ||
            ($user->hasRole('manager') && $schedule->barbershop->manager->id === $user->id) ||
            ($user->hasRole('recepcionist') && $schedule->barbershop->recepcionist->id === $user->id) ||
            $schedule->client->id === $user->id;
    }

    /**
     * Determine whether the user can destroy the model.
     */
    public function destroy(User $user, Schedule $schedule): bool
    {
        return $user->hasRole('admin') ||
            ($user->hasRole('manager') && $schedule->barbershop->manager->id === $user->id) ||
            ($schedule->status === 'scheduled' && $user->hasRole('recepcionist') && $schedule->barbershop->recepcionist->id === $user->id) ||
            $schedule->client->id === $user->id;
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
