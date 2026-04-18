<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class ZoomService
{
    private $clientId;
    private $clientSecret;
    private $accountId;
    private $baseUrl = 'https://api.zoom.us/v2';

    public function __construct()
    {
        $this->clientId = env('ZOOM_CLIENT_ID');
        $this->clientSecret = env('ZOOM_CLIENT_SECRET');
        $this->accountId = env('ZOOM_ACCOUNT_ID');
    }

    /**
     * Get OAuth Access Token
     */
    public function getAccessToken()
    {
        // Check Cache first
        if (Cache::has('zoom_access_token')) {
            return Cache::get('zoom_access_token');
        }

        try {
            $response = Http::asForm()->withBasicAuth($this->clientId, $this->clientSecret)
                ->post('https://zoom.us/oauth/token', [
                    'grant_type' => 'account_credentials',
                    'account_id' => $this->accountId,
                ]);

            if ($response->successful()) {
                $data = $response->json();
                $token = $data['access_token'];
                $expiresIn = $data['expires_in'] - 60; // Buffer time

                Cache::put('zoom_access_token', $token, $expiresIn);
                return $token;
            } else {
                Log::error('Zoom OAuth Failed: ' . $response->body());
                return null;
            }
        } catch (\Exception $e) {
            Log::error('Zoom OAuth Exception: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Create a Meeting
     */
    public function createMeeting($topic, $startTime, $duration, $password = null)
    {
        $token = $this->getAccessToken();
        if (!$token) return null;

        $data = [
            'topic' => $topic,
            'type' => 2, // Scheduled Meeting
            'start_time' => $startTime, // Check format: YYYY-MM-DDTHH:MM:SSZ
            'duration' => $duration,
            'timezone' => 'UTC', // Best practice
            'settings' => [
                'host_video' => true,
                'participant_video' => false, // Save bandwidth
                'join_before_host' => false,
                'mute_upon_entry' => true,
                'waiting_room' => true,
            ]
        ];

        if ($password) {
            $data['password'] = $password;
        }

        $response = Http::withToken($token)->post("{$this->baseUrl}/users/me/meetings", $data);

        if ($response->successful()) {
            return $response->json();
        }

        Log::error('Zoom Create Meeting Failed: ' . $response->body());
        return null;
    }

    /**
     * Delete a Meeting
     */
    public function deleteMeeting($meetingId)
    {
        $token = $this->getAccessToken();
        if (!$token) return false;

        $response = Http::withToken($token)->delete("{$this->baseUrl}/meetings/{$meetingId}");

        return $response->successful();
    }
}
