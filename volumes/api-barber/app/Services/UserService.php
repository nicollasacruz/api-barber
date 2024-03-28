<?php

namespace App\Services;

use App\Models\Barbershop;
use App\Models\User;
use App\Models\Service;
use App\Models\Schedule;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;

class UserService
{
    public function createBarber(array $data, $barbershop): User
    {
        $barber = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'barbershop_id' => $barbershop->id
        ]);

        $barber->role = ["barber", "user"];
        $barber->save();

        if (isset($data['services'])) {
            $services = Service::whereIn('id', $data['services'])->get();
            $barber->services()->attach($services);
        }

        return $barber;
    }

    public function updateBarber(User $barber, array $data, $barbershop): User
    {
        $barber->fill([
            'name' => $data['name'],
            'email' => $data['email'],
            'barbershop_id' => $barbershop->id,
        ]);

        if (isset($data['services'])) {
            $barber->services()->sync($data['services']);
        }
        if ($barber->isDirty()) {
            $barber->save();
        }

        return $barber;
    }

    public function deleteBarber(User $barber): User
    {
        $barber->delete();

        return $barber;
    }

    public function getBarberWithServices(User $barber): User
    {
        return $barber->load('services');
    }

    public function scheduleService(User $barber, array $data, Barbershop $barbershop): Schedule
    {
        $service = Service::find($data['service_id']);
        $startTime = Carbon::parse($data['date_scheduled']);
        $endTime = $startTime->copy()->addMinutes($service->duration);

        $schedule = Schedule::create([
            'amount' => $service->price,
            'start_date' => $startTime,
            'end_date' => $endTime,
            'barbershop_id' => $barbershop->id,
            'barber_id' => $barber->id,
            'client_id' => $data['client_id'],
            'service_id' => $service->id,
        ]);

        return $schedule;
    }

    public function checkTimeSlotAvailability(User $barber, $time, $duration, $date): bool
    {
        $schedules = $barber->schedules()->whereDate('start_date', $date)->get();

        foreach ($schedules as $schedule) {
            $startTime = Carbon::parse($schedule->start_date);
            $endTime = Carbon::parse($schedule->end_date);

            if ($time->equalTo($startTime)) {
                return false;
            }

            if ($time->equalTo($endTime)) {
                return true;
            }

            if ($time->between($startTime, $endTime)) {
                return false;
            }

            if ($time->copy()->addMinutes($duration)->between($startTime, $endTime) && !$time->equalTo($startTime)) {
                return false;
            }
        }

        return true;
    }
}
