<?php

namespace App\Http\Controllers;

use App\Models\FoodItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
            // Validate the request
            $validated = $request->validate([
                'name' => 'required|string|max:100',
                'description' => 'nullable|string',
                'price' => 'required|numeric|min:0',
                'image' => 'nullable|file|image|max:2048', // Use 'image' instead of 'image_url'
                'category_id' => 'required|integer|exists:categories,id',
                'available' => 'boolean',
                'available_day' => 'nullable|array',
                'available_day.*' => 'in:Sunday,Monday,Tuesday,Wednesday,Thursday,Friday,Saturday',
            ]);

            // Handle image upload
            if ($request->hasFile('image')) {
                // Store the image in the 'public/food-images' directory
                $imagePath = $request->file('image')->store('food-images', 'public');
                // Save the path to the database
                $validated['image_url'] = $imagePath;
            }

            // Convert available_day array to JSON
            if (isset($validated['available_day'])) {
                $validated['available_day'] = json_encode($validated['available_day']);
            }

            // Create the food item
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
            // Validate the request
            $validated = $request->validate([
                'name' => 'required|string|max:100',
                'description' => 'nullable|string',
                'price' => 'required|numeric|min:0',
                'image' => 'nullable|file|image|max:2048', // Use 'image' instead of 'image_url'
                'category_id' => 'required|integer|exists:categories,id',
                'available' => 'boolean',
                'available_day' => 'nullable|array',
                'available_day.*' => 'in:Sunday,Monday,Tuesday,Wednesday,Thursday,Friday,Saturday',
            ]);

            // Handle image upload
            if ($request->hasFile('image')) {
                // Delete the old image if it exists
                if ($foodItem->image_url && Storage::disk('public')->exists($foodItem->image_url)) {
                    Storage::disk('public')->delete($foodItem->image_url);
                }

                // Store the new image
                $imagePath = $request->file('image')->store('food-images', 'public');
                $validated['image_url'] = $imagePath;
            }

            // Convert available_day array to JSON
            if (isset($validated['available_day'])) {
                $validated['available_day'] = json_encode($validated['available_day']);
            }

            // Update the food item
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
            // Delete the image file if it exists
            if ($foodItem->image_url && Storage::disk('public')->exists($foodItem->image_url)) {
                Storage::disk('public')->delete($foodItem->image_url);
            }

            // Delete the food item
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
