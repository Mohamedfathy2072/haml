<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Baby;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BabyController extends Controller
{
    public function index()
    {
        // Ensure the user is authenticated
        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        // Get the authenticated user
        $user = Auth::user();

        // Fetch babies associated with the authenticated user
        $babies = Baby::where('user_id', $user->id)->get();

        return response()->json(['data' => $babies], 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'birthday' => '',
            'gender' => 'string',
            'weight' => 'numeric',
            'height' => 'numeric',
        ]);
        // Ensure the user is authenticated
        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        // Get the authenticated user
        $user = Auth::user();
//dd($user->id);
        // Create a new baby associated with the authenticated user
        $baby = new Baby([
            'user_id' => $user->id, // Set the user_id field

            'name' => $request->name,
            'birthday' => $request->birthday,
            'gender' => $request->gender,
            'weight' => $request->weight,
            'height' => $request->height,
        ]);

        $baby->save();

        return response()->json(['message' => 'Baby added successfully', 'data' => $baby], 201);
    }}
