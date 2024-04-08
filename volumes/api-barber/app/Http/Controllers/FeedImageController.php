<?php

namespace App\Http\Controllers;

use App\Models\FeedImage;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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
            $image->storeAs('public/images/feedImages/barber_' . $barber->id, $image_name);
        }

        $feedImage = FeedImage::create([
            'subtitle' => $request->subtitle,
            'image' =>  'public/images/feedImages/barber_' . $barber->id . '/' . $image_name,
            'user_id' => $barber->id,
            'likes_count' => 0,
        ]);

        $feedImage->addMediaFromRequest('image')
            ->toMediaCollection('images_feed');

        // Recupere a mídia recém-adicionada
        $media = $feedImage->getFirstMedia('images_feed');

        // Verifique se a mídia foi recuperada com sucesso
        // if ($media) {
        //     // Salve o URL de visualização e o URL da imagem
        //     $feedImage->url_preview = $media->getUrl('preview');
        //     $feedImage->url_image = $media->getUrl();
        //     $feedImage->save();
        // }

        return response()->json([
            'status' => true,
            'message' => 'Feed image created successfully',
            'data' => $feedImage->load('media')
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
        // $request->validate([
        //     'subtitle' => 'string|required',
        // ]);

        $validateUser = Validator::make(
            $request->all(),
            [
                'subtitle' => 'string|required',
            ]
        );

        if ($validateUser->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation error',
                'errors' => $validateUser->errors()
            ], 401);
        }

        $feedImage->fill([
            'subtitle' => $request->input('subtitle'),
        ]);

        if ($feedImage->isDirty()) {
            $feedImage->save();
        }

        return response()->json([
            'status' => true,
            'message' => 'Feed image updated successfully',
            'data' => $feedImage->load('media')
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(FeedImage $feedImage)
    {
        $feedImage->delete();

        return response()->json([
            'status' => true,
            'message' => 'Feed image deleted successfully',
            'data' => $feedImage
        ], 200);
    }
}
