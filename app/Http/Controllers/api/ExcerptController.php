<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Excerpt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;


class ExcerptController extends Controller
{
    public function index()
    {
        $excerpts = Excerpt::with('images')->get();

        // Transform excerpts to include image links
        $transformedExcerpts = $excerpts->map(function ($excerpt) {
            $excerptText = $excerpt->text; // Assuming 'text' is the field where the excerpt text is stored
            $imageLinks = $excerpt->images->pluck('image_path')->map(function ($path) {
                return asset($path); // Assuming 'image_path' is the field storing the image path
            })->toArray(); // Convert to array for JSON output

            return [
                'excerpt' => $excerpt->title,
                'description' => $excerpt->description,
                'hint' => $excerpt->hint,

                'images' => $imageLinks
            ];
        });

        return response()->json(['excerpts'=>$transformedExcerpts]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'hint' => 'required|string',
            'description' => 'required|string',
            'images.*' => '' // validate each image
        ]);

        $excerpt = Excerpt::create([
            'title' => $request->title,
            'hint' => $request->hint,
            'description' => $request->description,
        ]);

        // Handle multiple images upload

        // Handle multiple images upload
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                // Generate a unique filename for each image
                $filename = uniqid() . '.' . $image->getClientOriginalExtension();

                // Move the uploaded file to the desired directory
                $image->move(public_path('images'), $filename);

                // Save the image path to your database
                $excerpt->images()->create(['image_path' => 'images/' . $filename]);
            }
        }


        return response()->json($excerpt, 201);
    }


}
