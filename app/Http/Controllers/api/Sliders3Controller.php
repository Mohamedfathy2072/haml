<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Sliders3;
use Illuminate\Http\Request;

class Sliders3Controller extends Controller
{

    public function index()
    {
        $sliders = Sliders3::all();

        // Transform each slider to include the image URL
        $slidersWithUrls = $sliders->map(function ($slider) {
            return [
                'title' => $slider->title,
                'slider_path' => asset($slider->slider_path), // Constructing URL for the image
            ];
        });

        return response()->json(["sliders"=>$slidersWithUrls]);
    }
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'slider' => '', // validate single image
        ]);

        if ($request->hasFile('slider')) {
            $slider = $request->file('slider');
            $filename = uniqid() . '.' . $slider->getClientOriginalExtension();
            $slider->move(public_path('images/sliders3'), $filename);

            $sliderPath = 'images/sliders3/' . $filename;

            $slider = Sliders3::create([
                'title' => $request->title,
                'slider_path' => $sliderPath,
            ]);

            return response()->json($slider, 201);
        } else {
            return response()->json(['error' => 'No slider file uploaded.'], 400);
        }
    }
}
