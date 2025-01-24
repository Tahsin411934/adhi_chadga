<?php

namespace App\Http\Controllers;

use App\Models\FoodItem;
use Illuminate\Http\Request;

class FoodItemController extends Controller
{
    public function index()
    {
        try {
            $foodItems = FoodItem::with('category')->get(); // Fetch related category
            return response()->json($foodItems, 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to fetch food items.',
                'details' => $e->getMessage(),
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:100',
                'description' => 'nullable|string',
                'price' => 'required|numeric|min:0',
                'image_url' => 'nullable|file|image|max:2048',
                'category_id' => 'required|integer|exists:categories,id',
                'available' => 'boolean',
            ]);

            // Save image if provided
            if ($request->hasFile('image_url')) {
                $validated['image_url'] = $request->file('image_url')->store('food-images', 'public');
            }

            $foodItem = FoodItem::create($validated);

            return response()->json([
                'message' => 'Food item created successfully.',
                'data' => $foodItem,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to create food item.',
                'details' => $e->getMessage(),
            ], 400);
        }
    }

    public function show(FoodItem $foodItem)
    {
        return response()->json($foodItem, 200);
    }

    public function update(Request $request, FoodItem $foodItem)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:100',
                'description' => 'nullable|string',
                'price' => 'required|numeric|min:0',
                'image_url' => 'nullable|file|image|max:2048',
                'category_id' => 'required|integer|exists:categories,id',
                'available' => 'boolean',
            ]);

            // Update image if provided
            if ($request->hasFile('image_url')) {
                $validated['image_url'] = $request->file('image_url')->store('food-images', 'public');
            }

            $foodItem->update($validated);

            return response()->json([
                'message' => 'Food item updated successfully.',
                'data' => $foodItem,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to update food item.',
                'details' => $e->getMessage(),
            ], 400);
        }
    }

    public function destroy(FoodItem $foodItem)
    {
        try {
            $foodItem->delete();

            return response()->json([
                'message' => 'Food item deleted successfully.',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to delete food item.',
                'details' => $e->getMessage(),
            ], 400);
        }
    }
}
