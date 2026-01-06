<?php

namespace App\Http\Controllers;

use App\Models\Wishlist;
use App\Models\Food;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    private function formatImage($food)
    {
        if ($food && $food->image) {
            $food->image = asset('storage/' . $food->image);
        }
        return $food;
    }

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

        // Transform the data to return food items with properly formatted image URLs
        $foods = $wishlist->map(function ($item) {
            return $this->formatImage($item->food);
        });

        return response()->json($foods);
    }
}