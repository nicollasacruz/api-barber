<?php

namespace App\Http\Controllers;

use App\Models\Barber;
use App\Models\Barbershop;
use App\Models\Client;
use App\Models\Schedule;
use App\Models\Service;
use App\Models\User;
use App\Services\BarbershopService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function storeBarber(Request $request, Barbershop $barbershop)
    {
        $validateUser = Validator::make(
            $request->all(),
            [
                'name' => 'required',
                'email' => 'required|email|unique:users,email',
                'password' => 'required',
                'services' => ['array', function ($attribute, $value, $fail) {
                    $existingServiceIds = Service::pluck('id')->toArray();
                    foreach ($value as $serviceId) {
                        if (!in_array($serviceId, $existingServiceIds)) {
                            $fail("The selected service with ID $serviceId does not exist.");
                        }
                    }
                }],
            ]
        );

        if ($validateUser->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'validation error',
                'errors' => $validateUser->errors()
            ], 401);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'barbershop_id' => $barbershop->id
        ]);

        $user->role = ["barber", "user"];
        $user->save();
        $barber = new Barber();
        $barber->user_id = $user->id;
        $barber->barbershop_id = $barbershop->id;
        $barber->save();

        if ($request->services) {
            $services = Service::whereIn('id', $request->services)->get();
            $barber->services()->attach($services);
        }

        return response()->json([
            'status' => true,
            'message' => 'Barber Created Successfully',
            'data' => $user
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function showBarber(Request $request, Barbershop $barbershop, User $barber)
    {
        return response()->json([
            'status' => true,
            'message' => 'Getting resource successfully',
            'data' => $barber->with('services')->get(),
        ]);
    }

    public function update(Request $request, Barbershop $barbershop, Barber $barber)
    {
        $validateUser = Validator::make(
            $request->all(),
            [
                'name' => 'required',
                'email' => 'required|email|unique:users,email,' . $barber->user->id,
                'services' => ['array', function ($attribute, $value, $fail) {
                    $existingServiceIds = Service::pluck('id')->toArray();
                    foreach ($value as $serviceId) {
                        if (!in_array($serviceId, $existingServiceIds)) {
                            $fail("The selected service with ID $serviceId does not exist.");
                        }
                    }
                }],
            ]
        );

        if ($validateUser->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation error',
                'errors' => $validateUser->errors()
            ], 401);
        }

        $barber->user->fill([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'barbershop_id' => $barbershop->id,
        ]);

        if ($request->has('services')) {
            $barber->services()->sync($request->input('services'));
        }

        if ($barber->isDirty()) {
            $barber->save();
        }

        return response()->json([
            'status' => true,
            'message' => 'Barber Updated Successfully',
            'data' => $barber
        ], 200);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Barbershop $barbershop, User $user)
    {
        $user->delete();

        return response()->json([
            'status' => true,
            'message' => 'Barber Deleted Successfully',
            'data' => $user
        ], 200);
    }

    public function getAvailableSchedules(BarbershopService $barbershopService, Request $request, Barbershop $barbershop, Barber $barber): JsonResponse
    {
        $duration = $request->duration;
        $interval = 15;
        $availableSchedules = [];

        $date = date_create($request->date)->format('Y-m-d');

        // Obter os horários de funcionamento para o dia especificado
        $openingHours = $barbershopService->getBusinessHours($barbershop, $date);

        // Iterar sobre os horários de funcionamento e encontrar os horários disponíveis
        foreach ($openingHours as $intervalDay) {
            [$openingTime, $closingTime] = explode('-', $intervalDay);

            $startOfDay = Carbon::parse($openingTime);
            $endOfDay = Carbon::parse($closingTime);

            // Dividir o intervalo em segmentos de 15 minutos
            $time = $startOfDay->copy();
            while ($time->lte($endOfDay)) {
                // Verificar se o segmento de tempo está disponível
                if ($this->isTimeSlotAvailable($time, $duration, $barber, $date)) {
                    $availableSchedules[] = $time->format('H:i');
                }
                // Avançar para o próximo segmento de tempo
                $time = $time->addMinutes($interval);
            }
        }

        return response()->json([
            'status' => true,
            'message' => 'Available Schedules',
            'data' => $availableSchedules
        ]);
    }

    private function isTimeSlotAvailable($time, $duration, $barber, $date)
    {
        $newTime = $time->copy();
        // Obter todos os agendamentos para o dia especificado
        $schedules = $barber->schedules()->whereDate('start_date', $date)->get();

        // Verificar se o segmento de tempo está dentro de um horário já agendado
        foreach ($schedules as $schedule) {
            $startTime = Carbon::parse($schedule->start_date);
            $endTime = Carbon::parse($schedule->end_date);

            if ($newTime->equalTo($startTime)) {
                return false;
            }

            if ($newTime->equalTo($endTime)) {
                return true;
            }
            // Verificar se há sobreposição de horários
            if ($newTime->between($startTime, $endTime)) {
                return false;
            }

            // Verificar se há tempo de duração suficiente entre os horários
            if ($newTime->addMinutes($duration)->between($startTime, $endTime) && !!!$newTime->equalTo($startTime)) {
                return false;
            }
        }

        return true;
    }

    public function scheduleService(Request $request, Barbershop $barbershop, Barber $barber)
    {
        $validateUser = Validator::make(
            $request->all(),
            [
                'date_scheduled' => 'required|date',
                'client_id' => ['required', function ($attribute, $value, $fail) {
                    $existingClientIds = Client::pluck('id')->toArray();
                    if (!in_array($value, $existingClientIds)) {
                        $fail("The $attribute $value selected does not exist.");
                    }
                }],
                'service_id' => ['required', function ($attribute, $value, $fail) {
                    $existingServiceIds = Service::pluck('id')->toArray();
                    if (!in_array($value, $existingServiceIds)) {
                        $fail("The $attribute $value selected does not exist.");
                    }
                }],
            ]
        );

        if ($validateUser->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation error',
                'errors' => $validateUser->errors()
            ], 401);
        }

        $time = Carbon::parse($request->date_scheduled);
        $date = date_create($request->date_scheduled)->format('Y-m-d');
        $service = Service::find($request->service_id);

        if (!$this->isTimeSlotAvailable($time, $service->duration, $barber, $date)) {
            return response()->json([
                'status' => false,
                'message' => 'Time slot is not available',
            ], 404);
        }

        $startTime = Carbon::parse($request->date_scheduled);
        $endTime = $startTime->copy()->addMinutes($service->duration);

        $schedule = Schedule::create([
            'amount' => $service->price,
            'start_date' => $startTime,
            'end_date' => $endTime,
            'barbershop_id' => $barbershop->id,
            'barber_id' => $barber->id,
            'client_id' => $request->client_id,
            'service_id' => $service->id,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Schedule Created Successfully',
            'data' => $schedule
        ], 200);
    }

    public function updateProfile(Request $request, User $user)
    {
        $request->validate([
            'name' => 'string|min:4',
            'email' => 'email|unique:users,email',
            'contact' => 'string|min:11',
            'password' => 'confirmed|min:6',
            'profile_image' => 'image|mimes:jpeg,png,jpg,gif|max:20480',
        ]);

        $user->fill($request->only([
            'name',
            'email',
            'password'
        ]));

        if ($request->hasFile('profile_image')) {
            $user->clearMedia('profile');
            $user->addMediaFromRequest('profile_image')->toMediaCollection('profile');
        }

        if ($user->isDirty()) {
            $user->save();
        }

        return response()->json([
            'status' => true,
            'message' => 'User Updated Successfully',
            'data' => $user
        ], 200);
    }
}
