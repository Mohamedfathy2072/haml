<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Advice;
use Illuminate\Http\Request;

class AdviceController extends Controller
{
    public function index()
    {
        // Retrieve advices
        $advices = Advice::all();

        // Return advices as JSON response
        return response()->json(['data' => $advices], 200);
    }
    public function store(Request $request)
    {
        // Validate request data
        $request->validate([
            'title' => 'required|string',
            'desc' => 'required|string',
        ]);

        // Create and save advice
        $advice = new Advice([
            'title' => $request->title,
            'desc' => $request->desc,
        ]);
        $advice->save();

        // Return success response
        return response()->json(['message' => 'Advice added successfully', 'data' => $advice], 201);
    }
}
