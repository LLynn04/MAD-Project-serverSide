<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Food;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class FoodController extends Controller
{
    public function index(Request $request)
    {
        $query = Food::query();

        if ($request->has('category') && $request->category !== '') {
            $query->where('category', $request->category);
        }

        if ($request->has('search') && $request->search !== '') {
            $query->where('name', 'LIKE', '%' . $request->search . '%');
        }

        $foods = $query->get();

        return response()->json([
            'status' => 'success',
            'foods' => $foods
        ], 200);
    }


    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'ingredients' => 'required|string',
            'steps' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('images', 'public');
        }

        $food = Food::create([
            'name' => $request->name,
            'category' => $request->category,
            'ingredients' => $request->ingredients,
            'steps' => $request->steps,
            'image' => $imagePath
        ]);

        return response()->json($food, 201);
    }

    public function show($id)
    {
        $food = Food::find($id);
        if (!$food) {
            return response()->json(['message' => 'Food not found'], 404);
        }
        return response()->json([
            'status' => 'success',
            'food' => $food
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $food = Food::find($id);
        if (!$food) {
            return response()->json(['message' => 'Food not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'category' => 'sometimes|required|string|max:100',
            'ingredients' => 'sometimes|required|string',
            'steps' => 'sometimes|required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        if ($request->hasFile('image')) {


            if ($food->image && Storage::disk('public')->exists($food->image)) {
                Storage::disk('public')->delete($food->image);
            }


            $imagePath = $request->file('image')->store('images', 'public');
            $food->image = $imagePath;
        }


        $food->fill($request->only(['name', 'category', 'ingredients', 'steps']));

        $food->save();

        return response()->json([
            'status' => 'updated success',
            'food' => $food
        ], 200);
    }

    public function destroy($id)
    {
        $food = Food::find($id);
        if (!$food) {
            return response()->json(['message' => 'Food not found'], 404);
        }
        $food->delete();
        return response()->json(['message' => 'Food deleted successfully'], 200);
    }
}
