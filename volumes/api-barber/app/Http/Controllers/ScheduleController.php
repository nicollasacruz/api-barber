<?php

namespace App\Http\Controllers;

use App\Models\Barbershop;
use App\Models\Schedule;
use App\Models\Service;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Barbershop $barbershop)
    {
        $this->authorize('showCollection', Schedule::class);

        return response()->json([
            'status' => true,
            'data' => Schedule::all()
        ]);
    }

    public function store(Request $request, Barbershop $barbershop)
    {
        $this->authorize('store', Schedule::class);
    
        $request->validate([
            'date_scheduled' => 'required|date',
            'barber_id' => 'required|integer',
            'client_id' => 'required|integer',
            'service_id' => ['required', 'integer', function ($attribute, $value, $fail) {
                $existingServiceIds = Service::pluck('id')->toArray();
                if (!in_array($value, $existingServiceIds)) {
                    $fail("The selected service with ID $value does not exist.");
                }
            }],
        ]);
    
        $service = Service::find($request->service_id);
    
        $startDate = Carbon::parse($request->date_scheduled);
        $endDate = $startDate->copy()->addMinutes($service->duration);
    
        $schedule = Schedule::create([
            'amount' => $service->price,
            'status' => 'scheduled',
            'start_date' => $startDate,
            'end_date' => $endDate,
            'barbershop_id' => $barbershop->id,
            'barber_id' => $request->barber_id,
            'client_id' => $request->client_id,
            'service_id' => $service->id,
        ]);
    
        return response()->json([
            'status' => true,
            'message' => 'Schedule created successfully',
            'data' => $schedule,
        ]);
    }
    

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Schedule $schedule)
    {
        $request->user()->can("show", $schedule);

        return response()->json([
            'status' => true,
            'message' => 'Show scheduled resource successfully',
            'data' => $schedule,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Schedule $schedule)
    {
        $request->user()->can("update", $schedule);

        $schedule->update([
            'amount' => $request->amount,
            'status' => 'scheduled',
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'barber_id' => $request->barber_id,
            'client_id' => $request->client_id,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Schedule updated successfully',
            'data' => $schedule,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Schedule $schedule)
    {
        $request->user()->can("destroy", $schedule);

        $schedule->status === 'scheduled';
        $schedule->save();

        $schedule->delete();

        return response()->json([
            'status' => true,
            'message' => 'Schedule deleted successfully',
            'data' => [],
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function restore(Schedule $schedule, Request $request)
    {
        $request->user()->can("restore", $schedule);

        $schedule->restore();

        $schedule->status === 'scheduled';
        $schedule->save();

        return response()->json([
            'status' => true,
            'message' => 'Schedule restored successfully',
            'data' => $schedule,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function forceDestroy(Schedule $schedule, Request $request)
    {
        $request->user()->can("forceDestroy", $schedule);

        $schedule->delete();

        return response()->json([
            'status' => true,
            'message' => 'Schedule deleted successfully',
        ]);
    }

    
}
