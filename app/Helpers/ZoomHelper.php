<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Http;

class ZoomHelper
{
    public static function createMeeting($topic, $startTime, $duration)
    {
        // 1. Get the Host Email from .env
        $hostEmail = env('ZOOM_HOST_EMAIL');

        if (!$hostEmail) {
            dd("Error: ZOOM_HOST_EMAIL is missing in your .env file.");
        }

        // 2. Get Access Token
        $response = Http::asForm()
            ->withBasicAuth(env('ZOOM_CLIENT_ID'), env('ZOOM_CLIENT_SECRET'))
            ->post('https://zoom.us/oauth/token', [
                'grant_type' => 'account_credentials',
                'account_id' => env('ZOOM_ACCOUNT_ID'),
            ]);

        if ($response->failed()) {
            dd("Zoom Connection Failed! Check your Client ID and Secret in .env", $response->json());
        }

        $token = $response->json()['access_token'];

        // 3. Create Meeting (Fixed: Using Email instead of 'me')
        $meetingResponse = Http::withToken($token)->post("https://api.zoom.us/v2/users/{$hostEmail}/meetings", [
            'topic' => $topic,
            'type' => 2, // Scheduled Meeting
            'start_time' => $startTime,
            'duration' => $duration,
            'timezone' => 'Asia/Karachi',
            'settings' => [
                'host_video' => true,
                'participant_video' => false,
                'join_before_host' => false,
                'mute_upon_entry' => true,
                'waiting_room' => true,
            ]
        ]);

        if ($meetingResponse->failed()) {
            dd("Meeting Creation Error:", $meetingResponse->json());
        }

        return $meetingResponse->json();
    }
}
