<?php

namespace App\Http\Controllers;

use App\Models\FeedImage;
use App\Models\User;
use Illuminate\Http\Request;

class FeedImageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function getAllFeedImagesByBarbershop(Request $request)
    {
        $barbershop_id = $request->get('barbershop');

        $feedImages = FeedImage::when($barbershop_id, function ($query) use ($barbershop_id) {
            $query->where('isShow', true);
            $query->whereHas('user', function ($query) use ($barbershop_id) {
                $query->where('barbershop_id', $barbershop_id);
            });
        })->with('media')->get();

        return response()->json([
            'status' => true,
            'message' => $feedImages->count() ? 'FeedImages for the specified barbershop retrieved successfully' : 'Nothing to show',
            'data' => $feedImages
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, User $barber)
    {
        $data = $request->validate([
            'subtitle' => 'string|nullable',
            'image' => 'required|image',
        ]);

        $image = $request->file('image');
        if ($image) {
            $image_name = $image->getBasename() . '_image.' . $image->getClientOriginalExtension();
            $image->storeAs('public/images/feedImages', $image_name);
        }
        
        $feedImage = FeedImage::create([
            'subtitle' => $request->subtitle,
            'image' =>  'app/public/images/feedImages/' . $image_name,
            'user_id' => $barber->id,
            'likes_count' => 0,
        ]);

        $feedImage->addMediaFromRequest('image')
            ->toMediaCollection('images');

        return response()->json([
            'status' => true,
            'message' => 'Feed image created successfully',
            'data' => $feedImage
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(FeedImage $feedImage)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, FeedImage $feedImage)
    {
        $data = $request->validate([
            'subtitle' => 'string',
        ]);

        $feedImage->fill($data);

        $feedImage->save();

        return response()->json([
            'status' => true,
            'message' => 'Feed image updated successfully',
            'data' => $feedImage
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(FeedImage $feedImage)
    {
        //
    }
}
