<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Week;
use Illuminate\Http\Request;

class WeekController extends Controller
{
    public function index()
    {
        $weeks = Week::all();

        return response()->json([
            'status' => 'success',
            'data' => $weeks
        ]);
    }
}
