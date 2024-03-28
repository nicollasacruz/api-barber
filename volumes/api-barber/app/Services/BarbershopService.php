<?php

namespace App\Services;

use App\Models\Barbershop;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Log;

class BarbershopService
{
    public static function store($request): array
    {
        try {
            $icon = $request->file('icon');
            $cover_image = $request->file('cover_image');
            if ($icon && $cover_image) {
                $icon_name = $request->name . '_icon_image.' . $icon->getClientOriginalExtension();
                $icon->storeAs('public/images/barbershops', $icon_name);

                $cover_image_name = $request->name . '_cover_image.' . $cover_image->getClientOriginalExtension();
                $cover_image->storeAs('public/images/barbershops', $cover_image_name);
            }
            $barbershop = Barbershop::create([
                'name' => $request->name,
                'email' => $request->email,
                'address' => $request->address,
                'icon' => $icon ? 'app/public/images/barbershops/' . $icon_name : '',
                'cover_image' => $cover_image ? 'app/public/images/barbershops/' . $cover_image_name : '',
            ]);
            if ($icon && $cover_image) {
                $barbershop
                    ->addMedia(storage_path('app/public/images/barbershops/' . $icon_name))
                    ->toMediaCollection('icon');

                $barbershop
                    ->addMedia(storage_path('app/public/images/barbershops/' . $cover_image_name))
                    ->toMediaCollection('cover_image');
            }
            return [
                'status' => true,
                'message' => 'Barbershop created successfully',
            ];
        } catch (Exception $e) {
            Log::error(env('APP_URL') . ': ' . $e->getMessage() . "\n" . $e->getTraceAsString());
            return [
                'status' => false,
                'message' => 'Something went wrong',
            ];
        }
    }

    public static function update($request, Barbershop $barbershop): array
    {
        try {
            if ($request->hasFile('icon')) {
                if ($barbershop->getFirstMedia('icon')) {
                    $barbershop->getFirstMedia('icon')->delete();
                }

                $icon = $request->file('icon');
                $icon_name = $request->name . '_icon_image.' . $icon->getClientOriginalExtension();
                $icon->storeAs('public/images/barbershops', $icon_name);

                $barbershop
                    ->addMedia(storage_path('app/public/images/barbershops/' . $icon_name))
                    ->toMediaCollection('icon');
                $barbershop->icon = 'public/images/barbershops/' . $icon_name;
            }

            if ($request->hasFile('cover_image')) {

                if ($barbershop->getFirstMedia('cover_image')) {
                    $barbershop->getFirstMedia('cover_image')->delete();
                }

                $cover_image = $request->file('cover_image');
                $cover_image_name = $request->name . '_cover_image.' . $cover_image->getClientOriginalExtension();
                $cover_image->storeAs('public/images/barbershops', $cover_image_name);

                $barbershop
                    ->addMedia(storage_path('app/public/images/barbershops/' . $cover_image_name))
                    ->toMediaCollection('cover_image');

                $barbershop->cover_image = 'public/images/barbershops/' . $cover_image_name;
            }

            $barbershop->name = $request->name;
            $barbershop->email = $request->email;
            $barbershop->address = $request->address;
            $barbershop->save();

            return [
                'status' => true,
                'message' => 'Barbershop updated successfully',
            ];
        } catch (Exception $e) {
            Log::error(env('APP_URL') . ': ' . $e->getMessage() . "\n" . $e->getTraceAsString());
            return [
                'status' => false,
                'message' => 'Something went wrong',
            ];
        }
    }

    public function getBusinessHours(Barbershop $barbershop, $date)
    {
        $businessHours = $barbershop->businessHours;
        $startOfDay = [];

        // Determina o dia da semana com base na data fornecida
        $dayOfWeek = Carbon::parse($date)->dayOfWeek;

        // Mapeia os dias da semana
        $daysOfWeek = [
            'Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'
        ];

        // Acessa o horário de funcionamento correspondente ao dia especificado
        $businessHoursOfDay = $businessHours[$dayOfWeek];

        // Formata os horários de abertura e fechamento para o dia especificado
        $intervals = [];
        foreach ($businessHoursOfDay as $interval) {
            $intervals[] = $interval;
        }

        // Se não houver intervalos de abertura, o estabelecimento está fechado
        if (empty($intervals)) {
            return ["Closed"];
        }

        return $intervals;
    }
}
