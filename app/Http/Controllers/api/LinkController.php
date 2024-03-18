<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Link;
use Illuminate\Http\Request;

class LinkController extends Controller
{
    public function getAllLinks()
    {
        $links = Link::all();

        return response()->json(['links' => $links], 200);
    }
    public function addLink(Request $request)
    {
        // Validate the incoming request data
        $request->validate([
            'title' => 'required|string|max:255',
            'link' => '',
        ]);

        // Create a new link instance
        $link = new Link();
        $link->title = $request->title;
        $link->link = $request->link;
        $link->save();

        return response()->json(['message' => 'Link added successfully'], 201);
    }
}
