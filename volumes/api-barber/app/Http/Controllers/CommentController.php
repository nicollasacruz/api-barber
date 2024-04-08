<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\FeedImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(FeedImage $feedImage)
    {
        return response()->json([
            'status' => true,
            'message' => 'Comments retrieved successfully.',
            'data' => $feedImage->comments()->where('is_show', true)->with('user')->get()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(FeedImage $feedImage, Request $request)
    {
        $validateUser = Validator::make(
            $request->all(),
            [
                'content' => 'string|required',
            ]
        );

        if ($validateUser->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation error',
                'errors' => $validateUser->errors()
            ], 401);
        }

        $comment = $feedImage->comments()->create([
            'user_id' => auth()->id(),
            'content' => $request->content
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Comment created successfully.',
            'data' => $comment
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Comment $comment)
    {
        $validateUser = Validator::make(
            $request->all(),
            [
                'content' => 'string|required',
            ]
        );

        if ($validateUser->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation error',
                'errors' => $validateUser->errors()
            ], 401);
        }

        $comment->fill($request->all());
        if ($comment->isDirty()) {
            $comment->save();
        }

        return response()->json([
            'status' => true,
            'message' => 'Comment updated successfully.',
            'data' => $comment
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Comment $comment)
    {
        $comment->delete();

        return response()->json([
            'status' => true,
            'message' => 'Comment deleted successfully.'
        ]);
    }
}
