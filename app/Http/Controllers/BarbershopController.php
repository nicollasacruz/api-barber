<?php

namespace App\Http\Controllers;

use App\Models\Barbershop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class BarbershopController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('showAny', Barbershop::class);

        return response()->json([
            'status' => true,
            'data' => Barbershop::all()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('store', Barbershop::class);
    
        $request->validate([
            'name' => 'required|string|min:3|max:255|unique:barbershops',
            'icon' => 'required|image|max:1000000', // Limite de 25 MB
            'cover_image' => 'required|image|max:1000000', // Limite de 25 MB
            'mail' => 'required|email|max:100',
            'address' => 'required|max:200',
        ]);
    
        $icon = $request->file('icon');
        $icon_name = $request->name . '_icon_image.' . $icon->getClientOriginalExtension();
        $icon->storeAs('public/images/barbershops', $icon_name); // Armazenar imagem
    
        $cover_image = $request->file('cover_image');
        $cover_image_name = $request->name . '_cover_image.' . $cover_image->getClientOriginalExtension();
        $cover_image->storeAs('public/images/barbershops', $cover_image_name); // Armazenar imagem
    
        $barbershop = Barbershop::create([
            'name' => $request->name,
            'mail' => $request->mail,
            'address' => $request->address,
            'icon' => 'app/public/images/barbershops/' . $icon_name,
            'cover_image' => 'app/public/images/barbershops/' . $cover_image_name,
        ]);
    
        $barbershop
            ->addMedia(storage_path('app/public/images/barbershops/' . $icon_name)) // Adicionar mídia ao barbershop
            ->toMediaCollection('icon');
    
        $barbershop
            ->addMedia(storage_path('app/public/images/barbershops/' . $cover_image_name)) // Adicionar mídia ao barbershop
            ->toMediaCollection('cover_image');
    
        return response()->json([
            'status' => true,
            'data' => $barbershop,
        ]);
    }
    

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Barbershop $barbershop)
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
    public function update(Request $request, Barbershop $barbershop)
{
    dump($request->all(), $barbershop);
    // $request->user()->can("update", $barbershop);


    $request->validate([
        'name' => 'required|string|min:4|max:255|unique:barbershops,name,' . $barbershop->id,
        'icon' => 'image|max:1000000',
        'cover_image' => 'image|max:1000000',
        'mail' => 'required|string|max:100',
        'address' => 'required',
    ]);

    if ($request->hasFile('icon')) {
        $icon = $request->file('icon');
        $icon_name = $request->name . '_icon_image.' . $icon->getClientOriginalExtension();
        $icon->storeAs('public/images/barbershops', $icon_name);

        if ($barbershop->getFirstMedia('icon')) {
            $barbershop->getFirstMedia('icon')->delete();
        }

        $barbershop->icon = 'public/images/barbershops/' . $icon_name;
    }

    if ($request->hasFile('cover_image')) {
        $cover_image = $request->file('cover_image');
        $cover_image_name = $request->name . '_cover_image.' . $cover_image->getClientOriginalExtension();
        $cover_image->storeAs('public/images/barbershops', $cover_image_name);

        if ($barbershop->getFirstMedia('cover_image')) {
            $barbershop->getFirstMedia('cover_image')->delete();
        }

        $barbershop->cover_image = 'public/images/barbershops/' . $cover_image_name;
    }

    $barbershop->name = $request->name;
    $barbershop->mail = $request->mail;
    $barbershop->address = $request->address;
    $barbershop->save();

    return response()->json([
        'status' => true,
        'data' => 'Barbershop updated successfully',
    ]);
}


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Barbershop $barbershop)
    {
        $this->authorize('destroy', Barbershop::class);

        $barbershop->delete();

        return response()->json([
            'status' => true,
            'data' => 'Barbershop deleted successfully',
        ]);
    }

    public function getIcon(Barbershop $barbershop)
    {
        $media = $barbershop->getFirstMedia('icon');
        if (!$media) {
            abort(404);
        }

        return response()->file(Storage::path($media->getPath()));
    }

    public function getCoverImage(Barbershop $barbershop)
    {
        $media = $barbershop->getFirstMedia('cover_image');
        if (!$media) {
            abort(404);
        }

        return response()->file(Storage::path($media->getPath()));
    }
}
