<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{

    public function update(Request $request)
    {
        // Check if user is authenticated
        if (Auth::check()) {
            $user = Auth::user(); // Get the authenticated user

            // Validate the incoming request data
            $request->validate([
                'email' => 'email|unique:users,email,' . $user->id, // Unique email except current user
                'name' => 'string|max:255',
                'phone' => 'string',
                'country' => 'string',

            ]);

            // Update the user's profile data
            $user->email = $request->input('email', $user->email); // Update email if provided
            $user->name = $request->input('name', $user->name); // Update name if provided
            $user->phone = $request->input('phone', $user->phone); // Update phone if provided
            $user->country = $request->input('country', $user->country); // Update country if provided

            // Update other profile fields as needed

            $user->save();

            return response()->json([
                'message' => 'Profile updated successfully',
                'user' => $user
            ]);
        } else {
            // Handle unauthenticated user
            return response()->json([
                'error' => 'Unauthenticated user',
            ], 401);
        }
    }
}
