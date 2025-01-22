<?php

namespace App\Http\Controllers;

use App\Models\FoodItem;
use Illuminate\Http\Request;

class FoodItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $foodItems = FoodItem::all();
        return response()->json($foodItems);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'image_url' => 'nullable|string',
            'category_id' => 'required|integer',
            'available' => 'boolean',
        ]);

        $foodItem = FoodItem::create($validated);

        return response()->json([
            'message' => 'Food item created successfully.',
            'data' => $foodItem,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(FoodItem $foodItem)
    {
        return response()->json($foodItem);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(FoodItem $foodItem)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, FoodItem $foodItem)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'image_url' => 'nullable|string',
            'category_id' => 'required|integer|exists:categories,id',
            'available' => 'boolean',
        ]);

        $foodItem->update($validated);

        return response()->json([
            'message' => 'Food item updated successfully.',
            'data' => $foodItem,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(FoodItem $foodItem)
    {
        $foodItem->delete();

        return response()->json([
            'message' => 'Food item deleted successfully.',
        ]);
    }
}
