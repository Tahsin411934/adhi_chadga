<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    // Display a listing of the categories
    public function index()
    {
        $categories = Category::all();  // Fetch all categories from the database

        // Return the categories as JSON
        return response()->json($categories);
    }

    // Store a newly created category in storage
    public function store(Request $request)
    {
        // Validate the incoming request data
        $request->validate([
            'name' => 'required|string|max:100',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048' // Validate image file
        ]);

        // Handle image upload if present
        $imagePath = null;
        if ($request->hasFile('image')) {
            // Store the image in the 'public/categories' directory
            $imagePath = $request->file('image')->store('categories', 'public');
        }

        // Create a new category
        $category = Category::create([
            'name' => $request->name,
            'image' => $imagePath // Save the image path
        ]);

        // Return the newly created category as JSON
        return response()->json($category, 201);  // 201 Created
    }

    // Display the specified category
    public function show($id)
    {
        $category = Category::findOrFail($id);  // Find the category by ID

        // Return the category as JSON
        return response()->json($category);
    }

    // Update the specified category in storage
    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        // Validate the incoming request data
        $request->validate([
            'name' => 'required|string|max:100',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048' // Validate image file
        ]);

        // Handle image upload if present
        $imagePath = $category->image; // Default to existing image if no new image is provided
        if ($request->hasFile('image')) {
            // Delete the old image if it exists
            if ($category->image && Storage::disk('public')->exists($category->image)) {
                Storage::disk('public')->delete($category->image);
            }

            // Store the new image
            $imagePath = $request->file('image')->store('categories', 'public');
        }

        // Update the category
        $category->update([
            'name' => $request->name,
            'image' => $imagePath // Save the updated image path
        ]);

        // Return the updated category as JSON
        return response()->json($category);
    }

    // Remove the specified category from storage
    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        
        // Delete the image file from storage if it exists
        if ($category->image && Storage::disk('public')->exists($category->image)) {
            Storage::disk('public')->delete($category->image);
        }

        $category->delete();  // Delete the category

        // Return a success message
        return response()->json(['message' => 'Category deleted successfully.']);
    }
}
