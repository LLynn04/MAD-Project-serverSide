<?php

namespace App\Http\Controllers;


use App\Models\Comment;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    // store comment
    public function store(Request $request)
    {
        $request->validate([
            'food_id' => 'required|exists:foods,id',
            'comment' => 'required|string'
        ]);

        Comment::create([
            'user_id' => $request->user()->id,  
            'food_id' => $request->food_id,
            'comment' => $request->comment
        ]);

        return response()->json([
            'message' => 'Comment added successfully'
        ], 201);
    }

    // get comments by food
    public function getByFood($foodId)
    {
        $comments = Comment::with('user')
            ->where('food_id', $foodId)
            ->latest()
            ->get();

        return response()->json($comments);
    }

    // delete comment (optional)
    public function destroy($id)
    {
        Comment::findOrFail($id)->delete();

        return response()->json([
            'message' => 'Comment deleted'
        ]);
    }
}
