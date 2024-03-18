<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Name;
use Illuminate\Http\Request;

class NameController extends Controller
{
    public function names(Request $request)
    {
        $gender = $request->query('gender');

        if ($gender) {
            // Filter names by gender
            $names = Name::where('gender', $gender)->get();
        } else {
            // Get all names
            $names = Name::all();
        }

        return response()->json(['names' => $names], 200);
    }
    public function store(Request $request)
    {
        $request->validate([
            'gender' => 'required',
            'name' => 'required',
            'desc' => 'string',
        ]);

        $name = Name::create([
            'gender' => $request->input('gender'),
            'name' => $request->input('name'),
            'desc' => $request->input('desc'),
        ]);

        return response()->json(['message' => 'Name added successfully', 'name' => $name], 201);
    }}
