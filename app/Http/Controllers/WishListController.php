<?php

namespace App\Http\Controllers;

use App\Models\Wishlist;
use App\Models\Food;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    public function add(Request $request)
    {
        $request->validate([
            'food_id' => 'required|exists:foods,id'
        ]);

        $wishlist = Wishlist::firstOrCreate([
            'user_id' => $request->user()->id,
            'food_id' => $request->food_id
        ]);

        return response()->json(['message' => 'Added to wishlist', 'wishlist' => $wishlist]);
    }

    public function remove(Request $request)
    {
        Wishlist::where('user_id', $request->user()->id)
                ->where('food_id', $request->food_id)
                ->delete();

        return response()->json(['message' => 'Removed from wishlist']);
    }

    public function list(Request $request)
    {
        $wishlist = Wishlist::where('user_id', $request->user()->id)
                    ->with('food')
                    ->get();

        return response()->json(['wishlist' => $wishlist]);
    }
}

