<?php

namespace App\Http\Controllers;

use App\Models\Test;
use Illuminate\Http\Request;

class TestController extends Controller
{
    // Store data
    public function store(Request $request)
    {
        // Validate the request
        $request->validate([
            'name' => 'required|string|max:255',
            'age' => 'required|integer',
        ]);

        // Create a new record in the database
        Test::create([
            'name' => $request->name,
            'age' => $request->age,
        ]);

        return response()->json(['message' => 'Data stored successfully!'], 201);
    }

    // Show all data
    public function show()
    {
        // Retrieve all records
        $tests = Test::all();

        return response()->json($tests, 200);
    }
}
