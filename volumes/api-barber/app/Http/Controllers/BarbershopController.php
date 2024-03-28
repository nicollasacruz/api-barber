<?php

namespace App\Http\Controllers;

use App\Models\Barbershop;
use App\Services\BarbershopService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class BarbershopController extends Controller
{
    private BarbershopService $barbershopService;

    public function __construct(BarbershopService $barbershopService)
    {
        $this->barbershopService = $barbershopService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $this->authorize('showAny', Barbershop::class);

        return response()->json([
            'status' => true,
            'data' => Barbershop::with('media')->get()
        ]);
    }

    /**
     * Display a listing of the resource.
     */
    public function getBarbers(Barbershop $barbershop)
    {
        return response()->json([
            'status' => true,
            'message' => 'Getting resource successfully',
            'data' => $barbershop->barbers,
        ]);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $this->authorize('store', Barbershop::class);

        $request->validate([
            'name' => 'required|string|min:3|max:255|unique:barbershops',
            'icon' => 'image|mimes:jpeg,png,jpg,gif|max:1000000',
            'cover_image' => 'image|mimes:jpeg,png,jpg,gif|max:1000000',
            'email' => 'required|email|max:100',
            'address' => 'required|max:200',
        ]);

        $barbershop = $this->barbershopService::store($request);

        return response()->json([
            'status' => $barbershop['status'],
            'message' => $barbershop['message'],
        ]);
    }


    /**
     * Display the specified resource.
     */
    public function show(Request $request, Barbershop $barbershop): JsonResponse
    {
        $request->user()->can("show", $barbershop);

        return response()->json([
            'status' => true,
            'data' => $barbershop,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Barbershop $barbershop): JsonResponse
    {
        // $request->user()->can("update", $barbershop);

        $request->validate([
            'name' => 'string|min:4|max:255|unique:barbershops,name,' . $barbershop->id,
            'icon' => 'image|max:1000000',
            'cover_image' => 'image|max:1000000',
            'email' => 'string|max:100',
            'address' => 'string|max:250',
        ]);

        $barbershopResult = $this->barbershopService::update($request, $barbershop);

        return response()->json([
            'status' => $barbershopResult['status'],
            'message' => $barbershopResult['message'],
        ]);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Barbershop $barbershop, Request $request): JsonResponse
    {
        $request->user()->can("destroy", $barbershop);

        $barbershop->delete();

        return response()->json([
            'status' => true,
            'message' => 'Barbershop deleted successfully',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function restore(Barbershop $barbershop, Request $request): JsonResponse
    {
        $request->user()->can("restore", $barbershop);

        $barbershop->delete();

        return response()->json([
            'status' => true,
            'message' => 'Barbershop restored successfully',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function forceDestroy(Barbershop $barbershop, Request $request): JsonResponse
    {
        $request->user()->can("forceDestroy", $barbershop);

        $barbershop->delete();

        return response()->json([
            'status' => true,
            'message' => 'Barbershop deleted successfully',
        ]);
    }
}
