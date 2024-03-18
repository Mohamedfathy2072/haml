<?php
namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Baby;
use Carbon\Carbon;
use Illuminate\Http\Request;

class BabyMeasuersController extends Controller

{
    public function calculateHeightWeight(Request $request)
    {
        // Get height, weight, and date of measurement from the request
        $height = $request->input('height');
        $weight = $request->input('weight');
        $measurementDate = Carbon::parse($request->input('measurement_date'));

        // Retrieve the birthday from the baby table
        $birthday = $this->getBabyBirthday($request->input('id'));

        // Calculate gestational age in weeks based on birthday and measurement date
        $gestationalAgeInWeeks = $birthday->diffInWeeks($measurementDate);

        // Calculate the baby's height and weight range
        $babyData = $this->calculateBabyHeightWeight($gestationalAgeInWeeks);

        // Extract height and weight ranges
        $heightRange = explode('-', $babyData['height_range']);
        $weightRange = explode('-', $babyData['weight_range']);

        // Check if provided height and weight fall within the normal range
        $heightStatus = ($height >= $heightRange[0] && $height <= $heightRange[1]) ? 'Normal' : 'Abnormal';
        $weightStatus = ($weight >= $weightRange[0] && $weight <= $weightRange[1]) ? 'Normal' : 'Abnormal';

        // Prepare response
        $response = [
            'height_status' => $heightStatus,
            'height_range' => $babyData['height_range'],
            'weight_status' => $weightStatus,
            'weight_range' => $babyData['weight_range']
        ];

        // Return the status along with the range
        return response()->json($response);
    }    private function getBabyBirthday($babyId)
    {
        // Retrieve the baby's birthday from the database using the baby ID
        $baby = Baby::findOrFail($babyId);
        return Carbon::parse($baby->birthday);
    }
    private function calculateBabyHeightWeight($gestationalAgeInWeeks)
    {
        // Retrieve the gender of the baby from the database
        $gender = Baby::where('user_id', auth()->id())->value('gender');

        // Define growth data based on gender
        if ($gender == 'female') {
            $growthData = [
                [46.5, 52.7, 2.4, 4.2], // At birth
                [50, 57.4, 3.2, 5.4], // Month 1
                [53.2, 60.9, 4, 6.5], // Month 2
                [55.8, 63.8, 4.6, 7.4], // Month 3
                [58, 66.2,5.1,  8.1], // Month 4
                [59.9, 68.2, 5.5, 8.7], // Month 5
                [61.5, 70, 8.5, 9.2], // Month 6
                [62.9, 71.6, 6.1, 9.6], // Month 7
                [64.3, 73.2,6.6, 10.4], // Month 8
                [65.6, 74.7, 6.6, 10.4], // Month 9
                [66.8,76.1,6.8, 10.7], // Month 10
                [68, 77.5, 8.6,11.0], // Month 11
                [69.2, 78.9,10.1, 12.3], // Month 12
                // Add more data for other months...
            ];
        } else {
            $growthData = [
                [46.3, 53.4, 2.5,4.3], // At birth
                [51.1, 58.4, 3.4, 5.7], // Month 1
                [54.7, 62.2, 4.4, 7.0], // Month 2
                [57.6,65.3, 5.1, 7.9], // Month 3
                [60, 67.8, 5.6, 8.6], // Month 4
                [61.9, 69.9, 6.1, 9.2], // Month 5
                [63.6, 71.6, 6.4, 9.7], // Month 6
                [65.1,73.2, 6.7, 10.2], // Month 7
                [66.5, 74.4,7,  10.5], // Month 8
                [67.7, 76.2, 7.2, 10.9], // Month 9
                [69,77.6, 7.5, 11.2], // Month 10
                [70.2, 78.9, 7.4, 11.5], // Month 11
                [71.3, 80.2, 7.8,11.8], // Month 12
                // Add more data for other months...
            ];
        }

        // Calculate baby's height and weight based on gestational age and gender
        $heightRange = null;
        $weightRange = null;

        // If the gestational age is within the range of provided data
        if ($gestationalAgeInWeeks >= 0 && $gestationalAgeInWeeks <= 52) {
            // Determine the month index
            $monthIndex = floor($gestationalAgeInWeeks / 4); // 4 weeks in a month

            // Extract height and weight range for the given month
            if (isset($growthData[$monthIndex])) {
                $heightRange = $growthData[$monthIndex][0] . '-' . $growthData[$monthIndex][1]; // Height range
                $weightRange = $growthData[$monthIndex][2] . '-' . $growthData[$monthIndex][3]; // Weight range
            } else {
                // Handle out of range gestational age
                $heightRange = 'Out of range';
                $weightRange = 'Out of range';
            }
        } else {
            // Handle out of range gestational age
            $heightRange = 'Out of range';
            $weightRange = 'Out of range';
        }

        return [
            'height_range' => $heightRange,
            'weight_range' => $weightRange,
        ];
    }


}
