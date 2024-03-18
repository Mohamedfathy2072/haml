<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Notification;

use Illuminate\Support\Facades\Validator;

class NotificationsController extends Controller
{
    public function getnotifications(Request $request)
    {
        $notifications =Notification::get();

        return response()->json(['notifications' => $notifications]);
    }
    public function sendNotification(Request $request)
    {
        // Validate the incoming request

        $validator = Validator::make($request->all(), [
            'user_id' => 'exists:users,id',
            'title' => 'string',
            'body' => 'string',
            'image' => 'url', // You may add more validation for the image URL
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        // Retrieve the device token for the specified user
        $user = User::findOrFail($request->user_id);
        $deviceToken = $user->fcm;
//dd($deviceToken);
        // Send notification
        $this->sendNotificationToToken($deviceToken, $request->title, $request->body, $request->image);

        return response()->json(['message' => 'Notification sent successfully']);
    }

    private function sendNotificationToToken($token, $title, $body, $image = null)
    {
        $fields = [
            'registration_ids' => [$token],
            'notification' => [
                'title' => $title,
                'body' => $body,
                'image' => $image,
                'sound' => 'default',
                'click_action' => "OPEN_PRODUCT",
            ],
            'data' => [
                'custom_key' => 'custom_value',
                'click_action' => "OPEN_PRODUCT",
                'notification_type' => 'open_app',
                'image' => $image, // Move the image URL to the data payload
                'user_id' => $token, // You can use the token as the user ID in the data payload
            ],
            'priority' => 'high',
        ];

        $response = Http::withHeaders([
            'Authorization' => 'key=' . 'AAAAjdyFSqY:APA91bGb17jHz-kujRLVeBLlYnJsfDXSedCqPVKIAir4P2pC0KwlWpHVmspIWE9O5uo39LQzplmNt0hsL6R4VensyX72ZrZzFvTFjZbvb0pi1e1MUFqjhzVMZ_e7dS0u1YCoYAM44D8h'
        ])->post("https://fcm.googleapis.com/fcm/send", $fields);
    }
}
