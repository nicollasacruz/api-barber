<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|min:3|max:255',
            'description' => 'string|min:3|max:255',
            'icon' => 'image|max:1000000',
            'price' => 'required|numeric|between:0,999999.99',
            'barbershop_id' => 'required|integer|min:1',
        ]);

        $icon = $request->file('icon');
        $cover_image = $request->file('cover_image');
        if ($icon) {
            $icon_name = $request->name . '_icon_image.' . $icon->getClientOriginalExtension();
            $icon->storeAs('public/images/services', $icon_name);

        }
        $service = Service::create([
            'barbershop_id' => $request->barbershop_id,
            'name' => $request->name,
            'description' => $request->mail,
            'price' => $request->address,
            'icon' => $icon ? 'app/public/images/services/' . $icon_name : '',
        ]);

        if ($icon && $cover_image) {
            $service
                ->addMedia(storage_path('app/public/images/services/' . $icon_name))
                ->toMediaCollection('services');
        }
        return response()->json([
            'status' => true,
            'message' => 'service created successfully',
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Service $service)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Service $service)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Service $service)
    {
        //
    }
}
