<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Pressure;
use App\Models\Sugar;
use App\Models\User;
use App\Models\Weight;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use MohamedSabil83\LaravelHijrian\Facades\Hijrian;

class PregnancyCalculatorController extends Controller
{
    public function calculate(Request $request)
    {
        // Validate input data
        $request->validate([
            'calendar_type' => 'required|in:gregorian,hejri',
            'year' => 'required|numeric',
            'month' => 'required|numeric',
            'day' => 'required|numeric',
        ]);

        // Get the input data
        $calendarType = $request->input('calendar_type');
        $year = $request->input('year');
        $month = $request->input('month');
        $day = $request->input('day');


// Create Carbon instance for the last menstrual cycle based on the calendar type
        if ($calendarType === 'gregorian') {
            $lastMenstrualCycle = Carbon::createFromDate($year, $month, $day);
        } elseif ($calendarType === 'hejri') {
            $lastMenstrualCycle = Carbon::createFromFormat('d-m-Y', $day . '-' . $month . '-' . $year, 'UTC')->locale('ar');
        }
        // Save the last menstrual cycle date to the user's period column
        $user = User::find(auth()->user()->id); // Assuming you're using authentication
        $user->period = $lastMenstrualCycle;
        $user->save();
        // Calculate the expected due date
        $expectedDueDate = $lastMenstrualCycle->copy()->addDays(280);
        $dateOfEnrichment = $lastMenstrualCycle->copy()->addDays(14);

        // Calculate other pregnancy-related data
        if ($year<2000){
            $lastMenstrualCycle = Hijrian::gregory($year . '-' . $month . '-' . $day);

        }
        $orderOfMonthOfPregnancy = $lastMenstrualCycle->diffInMonths();
        $orderOfWeekOfPregnancy = $lastMenstrualCycle->diffInWeeks();

        // Calculate the pregnancy stage
        if ($orderOfWeekOfPregnancy < 13) {
            $pregnancyStage = 'First Trimester';
        } elseif ($orderOfWeekOfPregnancy < 28) {
            $pregnancyStage = 'Second Trimester';
        } else {
            $pregnancyStage = 'Third Trimester';
        }

        $daysOfPregnancy = $lastMenstrualCycle->diffInDays();
        $durationOfPregnancySoFar = $lastMenstrualCycle->diffInWeeks() . ' weeks and ' . $lastMenstrualCycle->diffInDays() % 7 . ' days';
        $durationOfPregnancyInWeeks = $lastMenstrualCycle->diffInWeeks();

        // Determine the zodiac sign (horoscope)
        $zodiacSign = $expectedDueDate->format('F') . ' ' . $expectedDueDate->day;

        // Calculate the remaining period of pregnancy
        $expectedDueDate2 = $lastMenstrualCycle->copy()->addDays(280);
        $remainingPeriodInDays = $expectedDueDate2->diffInDays();
        $remainingPeriodInWeeks = intval($remainingPeriodInDays / 7);
        $remainingPeriodInMonths = intval($remainingPeriodInWeeks / 4);

        // Calculate estimated baby weight and height based on the number of weeks of pregnancy
        $estimatedBabyData = $this->estimateBabyWeightAndHeight($orderOfWeekOfPregnancy);

        // Calculate the season of the expected due date
// Calculate the season of the expected due date based on the month
        $monthOfDueDate = $expectedDueDate->month;

        if ($monthOfDueDate >= 3 && $monthOfDueDate <= 5) {
            $season = 'Spring';
        } elseif ($monthOfDueDate >= 6 && $monthOfDueDate <= 8) {
            $season = 'Summer';
        } elseif ($monthOfDueDate >= 9 && $monthOfDueDate <= 11) {
            $season = 'Autumn';
        } else {
            $season = 'Winter';
        }
        $dayOfTheWeek = $expectedDueDate->translatedFormat('l');

        $monthOfDueDate = $expectedDueDate->month;
        $dayOfDueDate = $expectedDueDate->day;
        $zodiacSign = '';

        if (($monthOfDueDate == 3 && $dayOfDueDate >= 21) || ($monthOfDueDate == 4 && $dayOfDueDate <= 19)) {
            $zodiacSign = 'الحَمَل';
        } elseif (($monthOfDueDate == 4 && $dayOfDueDate >= 20) || ($monthOfDueDate == 5 && $dayOfDueDate <= 20)) {
            $zodiacSign = 'الثور';
        } elseif (($monthOfDueDate == 5 && $dayOfDueDate >= 21) || ($monthOfDueDate == 6 && $dayOfDueDate <= 20)) {
            $zodiacSign = 'الجوزاء';
        } elseif (($monthOfDueDate == 6 && $dayOfDueDate >= 21) || ($monthOfDueDate == 7 && $dayOfDueDate <= 22)) {
            $zodiacSign = 'السرطان';
        } elseif (($monthOfDueDate == 7 && $dayOfDueDate >= 23) || ($monthOfDueDate == 8 && $dayOfDueDate <= 22)) {
            $zodiacSign = 'الاسد';
        } elseif (($monthOfDueDate == 8 && $dayOfDueDate >= 23) || ($monthOfDueDate == 9 && $dayOfDueDate <= 22)) {
            $zodiacSign = 'العَذْراء';
        } elseif (($monthOfDueDate == 9 && $dayOfDueDate >= 23) || ($monthOfDueDate == 10 && $dayOfDueDate <= 22)) {
            $zodiacSign = 'الميزان';
        } elseif (($monthOfDueDate == 10 && $dayOfDueDate >= 23) || ($monthOfDueDate == 11 && $dayOfDueDate <= 21)) {
            $zodiacSign = 'العقرب';
        } elseif (($monthOfDueDate == 11 && $dayOfDueDate >= 22) || ($monthOfDueDate == 12 && $dayOfDueDate <= 21)) {
            $zodiacSign = 'القوس';
        } elseif (($monthOfDueDate == 12 && $dayOfDueDate >= 22) || ($monthOfDueDate == 1 && $dayOfDueDate <= 19)) {
            $zodiacSign = 'الجَدْي';
        } elseif (($monthOfDueDate == 1 && $dayOfDueDate >= 20) || ($monthOfDueDate == 2 && $dayOfDueDate <= 18)) {
            $zodiacSign = 'الدلو';
        } elseif (($monthOfDueDate == 2 && $dayOfDueDate >= 19) || ($monthOfDueDate == 3 && $dayOfDueDate <= 20)) {
            $zodiacSign = 'الحوت';
        }
        return response()->json([
            'expected_due_date' => $expectedDueDate->toDateString(),
            'date_of_Enrichment' => $dateOfEnrichment->toDateString(),
            'order_of_month_of_pregnancy' => $orderOfMonthOfPregnancy,
            'order_of_week_of_pregnancy' => $orderOfWeekOfPregnancy,
            'pregnancy_stage' => $pregnancyStage,
            'days_of_pregnancy' => $daysOfPregnancy,
            'duration_of_pregnancy_so_far' => $durationOfPregnancySoFar,
            'duration_of_pregnancy_in_weeks' => $durationOfPregnancyInWeeks,
            'remaining_period_of_pregnancy_in_months' => $remainingPeriodInMonths . ' months, ',
            'remaining_period_of_pregnancy_in_weeks' => $remainingPeriodInWeeks . ' weeks',
            'remaining_period_of_pregnancy_in_days' =>  $remainingPeriodInDays . ' days',
            'estimated_baby_weight' => $estimatedBabyData['estimated_baby_weight'],
            'estimated_baby_height' => $estimatedBabyData['estimated_baby_height'],
            'day_of_pregnancy' =>$dayOfTheWeek,
            'zodiac_sign' => $zodiacSign,
            'season_of_pregnancy' => $season,
        ]);
    }

    // Function to get the season based on the month
    private function getSeason($month)
    {
        $seasons = [
            'Spring' => [3, 4, 5],
            'Summer' => [6, 7, 8],
            'Autumn' => [9, 10, 11],
            'Winter' => [12, 1, 2],
        ];

        foreach ($seasons as $season => $months) {
            if (in_array($month, $months)) {
                return $season;
            }
        }

        return 'Unknown';
    }

    // Function to estimate baby weight and height based on weeks of pregnancy
    private function estimateBabyWeightAndHeight($weeksOfPregnancy) {
        // Define some constants for estimation (these values may vary based on different sources)
        $averageWeightGainPerWeek = 0.22; // in kilograms
        $averageHeightGainPerWeek = 0.5;   // in centimeters

        // Estimate baby weight and height based on weeks of pregnancy
        $estimatedBabyWeight = $weeksOfPregnancy * $averageWeightGainPerWeek;
        $estimatedBabyHeight = $weeksOfPregnancy * $averageHeightGainPerWeek;

        return [
            'estimated_baby_weight' => $estimatedBabyWeight,
            'estimated_baby_height' => $estimatedBabyHeight,
        ];
    }
    public function index(Request $request)
    {
        // Retrieve weight records for the authenticated user
        $user = $request->user(); // This will automatically retrieve the authenticated user
        $weights = $user->weights()->get();

        // Return the weight records as JSON response
        return response()->json(['weights'=>$weights]);
    }
    public function checkWeightGain(Request $request)
    {
        // Check if user is authenticated
        if (!auth()->check()) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        $validator = Validator::make($request->all(), [
            'pre_pregnancy_weight' => 'required|numeric',
            'current_weight' => 'required|numeric',
            'date_of_current_weight' => 'required|date',
            'last_menstrual_cycle' => 'required|date',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 400);
        }

        $user = auth()->user();

        // Create a new weight record
        $weight = new Weight();
        $weight->pre_pregnancy_weight = $request->input('pre_pregnancy_weight');
        $weight->current_weight = $request->input('current_weight');
        $weight->date_of_current_weight = Carbon::parse($request->input('date_of_current_weight'));
        $weight->last_menstrual_cycle = Carbon::parse($request->input('last_menstrual_cycle'));
        $user->weights()->save($weight);

        // Calculate the weight gain during pregnancy
        $weightGain = $weight->current_weight - $weight->pre_pregnancy_weight;

        // Calculate the stage of pregnancy based on the date of the current weight
        $weeksOfPregnancy = Carbon::now()->diffInWeeks($weight->last_menstrual_cycle);

        // Calculate the recommended weight gain based on the stage of pregnancy
        $recommendedWeightGain = $this->getRecommendedWeightGain($weeksOfPregnancy);

        // Check if the weight gain falls within the recommended range
        $isNormal = $weightGain >= $recommendedWeightGain['min'] && $weightGain <= $recommendedWeightGain['max'];

        return response()->json([
            'pre_pregnancy_weight' => $weight->pre_pregnancy_weight,
            'current_weight' => $weight->current_weight,
            'date_of_current_weight' => $weight->date_of_current_weight->toDateString(),
            'weight_gain' => $weightGain,
            'recommended_weight_gain' => $recommendedWeightGain,
            'is_normal' => $isNormal,
        ]);
    }

// Add this function to get the recommended weight gain based on the stage of pregnancy
    private function getRecommendedWeightGain($weeksOfPregnancy)
    {
        // Define recommended weight gain ranges for different stages of pregnancy
        $recommendedWeightGains = [
            ['min' => 0.5, 'max' => 2.0],  // Example: 1st trimester
            ['min' => 0.4, 'max' => 1.0],  // Example: 2nd trimester
            ['min' => 0.3, 'max' => 0.6],  // Example: 3rd trimester
        ];

        // Determine the stage of pregnancy based on weeks
        if ($weeksOfPregnancy <= 12) {
            return $recommendedWeightGains[0];
        } elseif ($weeksOfPregnancy <= 28) {
            return $recommendedWeightGains[1];
        } else {
            return $recommendedWeightGains[2];
        }
    }

    public function index2(Request $request)
    {
        // Retrieve sugars associated with the authenticated user
        $sugars = $request->user()->sugars()->get();

        // Return the retrieved sugars as JSON response
        return response()->json(["sugars"=>$sugars]);
    }
    public function checkMeasuringSugar(Request $request)
    {
        // Validate the incoming data
        $validator = Validator::make($request->all(), [
            'measuring_sugar' => 'required|numeric|min:0',
            'date_of_measurement' => 'required|date',
        ]);

        // If validation fails, return error response
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 400);
        }

        // Extract data from the request
        $measuringSugar = $request->input('measuring_sugar');
        $dateOfMeasurement = Carbon::parse($request->input('date_of_measurement'));

        // Determine the message based on sugar level
        $message = '';
        if ($measuringSugar < 80) {
            $message = 'Lower';
        } elseif ($measuringSugar > 160) {
            $message = 'High';
        } else {
            $message = 'Normal';
        }

        // Create a new sugar record in the database
        $sugar = new Sugar();
        $sugar->measuring_sugar = $measuringSugar;
        $sugar->date_of_measurement = $dateOfMeasurement;
        // Assuming the authenticated user is making the request
        $sugar->user_id = auth()->user()->id; // Adjust accordingly if using different authentication
        $sugar->save();

        // Return response with the stored data and message
        return response()->json([
            'measuring_sugar' => $measuringSugar,
            'date_of_measurement' => $dateOfMeasurement->toDateString(),
            'message' => $message,
        ]);
    }
    public function checkMeasuringPressure(Request $request)
    {
        // Validate the incoming data
        $validator = Validator::make($request->all(), [
            'highest_pressure' => 'required|numeric|min:0',
            'lowest_pressure' => 'required|numeric|min:0',
            'date_of_measurement' => 'required|date',
        ]);

        // If validation fails, return error response
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 400);
        }

        // Extract data from the request
        $highestPressure = $request->input('highest_pressure');
        $lowestPressure = $request->input('lowest_pressure');
        $dateOfMeasurement = Carbon::parse($request->input('date_of_measurement'));

        // Determine the message based on pressure levels
        $message = '';
        if ($highestPressure < 70 || $lowestPressure < 70) {
            $message = 'Low';
        } elseif (($highestPressure >= 100 && $highestPressure <= 120 && $lowestPressure >= 70 && $lowestPressure <= 80) ||
            ($highestPressure >= 120 && $highestPressure <= 140 && $lowestPressure >= 80 && $lowestPressure <= 90)) {
            $message = 'Normal';
        } else {
            $message = 'High';
        }

        // Create a new pressure record in the database
        $pressure = new Pressure();
        $pressure->highest_pressure = $highestPressure;
        $pressure->lowest_pressure = $lowestPressure;
        $pressure->date_of_measurement = $dateOfMeasurement;
        // Assuming the authenticated user is making the request
        $pressure->user_id = auth()->user()->id; // Adjust accordingly if using different authentication
        $pressure->save();

        // Return response with the stored data and message
        return response()->json([
            'highest_pressure' => $highestPressure,
            'lowest_pressure' => $lowestPressure,
            'date_of_measurement' => $dateOfMeasurement->toDateString(),
            'message' => $message,
        ]);
    }
    public function index3(Request $request)
    {
        // Retrieve pressures associated with the authenticated user
        $pressures = $request->user()->pressures()->get();

        // Return the retrieved pressures as JSON response
        return response()->json(["pressures"=>$pressures]);
    }
}
