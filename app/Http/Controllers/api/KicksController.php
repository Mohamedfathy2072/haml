<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\BabyKick;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KicksController extends Controller
{
    public function index(Request $request)
    {
        // Ensure user is authenticated
        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        // Retrieve baby kicks records for the authenticated user
        $user = Auth::user();
        $babyKicks = $user->babyKicks()->get();

        // Return the baby kicks records as JSON response
        return response()->json(["kicks"=>$babyKicks]);
    }
    public function checkPregnancy(Request $request)
    {
        // Validate the incoming request data
        $data = $request->validate([
            'number_of_kicks' => '',
            'start_hour' => '',
            'end_hour' => '',
        ]);

        // Store the validated data in the baby_kicks table
        $babyKick = new BabyKick();
        $babyKick->number_of_kicks = $data['number_of_kicks'];
        // Format start_hour and end_hour properly
        $babyKick->start_hour = now()->format('Y-m-d') . ' ' . $data['start_hour'] . ':00';
        $babyKick->end_hour = now()->format('Y-m-d') . ' ' . $data['end_hour'] . ':00';
        // Assuming you have authenticated user, you can retrieve the user ID like this:
        $babyKick->user_id = auth()->user()->id;
        $babyKick->save();

        // Define thresholds for normality
        $normalKicksThreshold = 10; // Number of kicks considered normal
        $normalKicksDuration = 60; // Duration in minutes considered normal for kicks to occur

        // Calculate duration between start and end hours
        $start = strtotime($babyKick->start_hour);
        $end = strtotime($babyKick->end_hour);
        $durationInMinutes = ($end - $start) / 60;

        // Check if the number of kicks is within the normal range and if the duration is sufficient
        $isNormal = ($data['number_of_kicks'] >= $normalKicksThreshold && $durationInMinutes >= $normalKicksDuration);

        // Provide a message indicating the result
        $message = $isNormal ? 'Pregnancy is normal' : 'Pregnancy might be abnormal. Please consult a doctor.';

        // Return the response
        return response()->json([
            'is_normal' => $isNormal,
            'message' => $message,
        ]);
    }
    // Controller method to handle the weight comparison
    public function compareWeight(Request $request)
    {
        // Validate incoming request
        $request->validate([
            'weight_before_pregnancy' => 'required|numeric',
            'current_weight' => 'required|numeric',
            'current_weight_date' => 'required|date',
        ]);

        // Get user inputs
        $weightBeforePregnancy = $request->input('weight_before_pregnancy');
        $currentWeight = $request->input('current_weight');
        $currentWeightDate = $request->input('current_weight_date');

        // Calculate normal weight (you need to implement this logic)
        $normalWeight = $this->calculateNormalWeight($weightBeforePregnancy);

        // Compare current weight with normal weight
        if ($currentWeight > $normalWeight) {
            $status = 'Higher than normal';
        } elseif ($currentWeight < $normalWeight) {
            $status = 'Less than normal';
        } else {
            $status = 'Normal';
        }

        // Return response
        return response()->json([
            'status' => $status,
            'normal_weight' => $normalWeight,
            'current_weight_date' => $currentWeightDate,
        ]);
    }
// Method to calculate the normal weight based on pre-pregnancy weight
    private function calculateNormalWeight($weightBeforePregnancy)
    {
        // Example: Assume a simple formula to calculate normal weight during pregnancy
        // You should replace this with a medically accurate formula or guidelines
        // This is just a placeholder and may not represent actual medical advice

        // Assuming a 0.5 kg (500 grams) increase per week is normal during pregnancy
        $normalWeightIncreasePerWeek = 0.5;

        // Assuming a pregnancy lasts for 40 weeks (standard gestation period)
        $pregnancyWeeks = 40;

        // Calculate the total expected weight gain during pregnancy
        $expectedWeightGain = $normalWeightIncreasePerWeek * $pregnancyWeeks;

        // Calculate the normal weight based on pre-pregnancy weight and expected weight gain
        $normalWeight = $weightBeforePregnancy + $expectedWeightGain;

        return $normalWeight;
    }

}
