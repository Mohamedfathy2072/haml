<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Name;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    public function favorites(Request $request)
    {
        $user = $request->user(); // Assuming you're using Laravel's built-in authentication

        $favorites = $user->favoriteNames()->get();

        return response()->json(['favorites' => $favorites]);
    }
    public function favorite(Request $request, $nameId)
    {
        $user = $request->user(); // Assuming you're using Laravel's built-in authentication

        $name = Name::findOrFail($nameId);

        $user->favoriteNames()->syncWithoutDetaching($name);

        return response()->json(['message' => 'Name favorited successfully']);
    }

    public function unfavorite(Request $request, $nameId)
    {
        $user = $request->user();

        $name = Name::findOrFail($nameId);

        $user->favoriteNames()->detach($name);

        return response()->json(['message' => 'Name unfavorited successfully']);
    }
}
