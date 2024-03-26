<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('showCollection', Schedule::class);

        return response()->json([
            'status' => true,
            'data' => Schedule::all()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('store', Schedule::class);

        $request->validate([
            'amount' => 'required|numeric|between:0,999999.99',
            'start_date' => 'required|date',
            'end_date' => 'required|after:start_date',
            'barbershop_id' => 'required|numeric',
            'barber_id' => 'required|numeric',
            'client_id' => 'required|numeric',
        ]);

        $schedule = Schedule::create([
            'amount' => $request->amount,
            'status' => 'scheduled',
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'barbershop_id' => $request->barbershop_id,
            'barber_id' => $request->barber_id,
            'client_id' => $request->client_id,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Schedule created successfully',
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
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Schedule $schedule)
    {
        $request->user()->can("destroy", $schedule);

        $schedule->delete();

        return response()->json([
            'status' => true,
            'message' => 'Schedule deleted successfully',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function restore(Schedule $schedule, Request $request)
    {
        $request->user()->can("restore", $schedule);

        $schedule->delete();

        return response()->json([
            'status' => true,
            'message' => 'Schedule restored successfully',
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
