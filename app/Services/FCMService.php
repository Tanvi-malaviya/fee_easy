<?php

namespace App\Services;

use Google\Auth\Credentials\ServiceAccountCredentials;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FCMService
{
    protected string $projectId;
    protected string $credentialsPath;

    public function __construct()
    {
        $this->projectId = config('services.firebase.project_id') ?? '';
        $path = config('services.firebase.credentials_path') ?? 'storage/app/firebase_credentials.json';
        
        // Resolve path absolute if relative
        $this->credentialsPath = str_starts_with($path, '/') || str_contains($path, ':\\') 
            ? $path 
            : base_path($path);
    }

    /**
     * Get OAuth 2.0 Access Token for Firebase HTTP v1 API
     */
    public function getAccessToken(): ?string
    {
        if (!file_exists($this->credentialsPath)) {
            Log::error("FCM Credentials file not found at: {$this->credentialsPath}");
            return null;
        }

        try {
            $credentials = new ServiceAccountCredentials(
                ['https://www.googleapis.com/auth/firebase.messaging'],
                $this->credentialsPath
            );

            $token = $credentials->fetchAuthToken();
            return $token['access_token'] ?? null;
        } catch (\Exception $e) {
            Log::error("Failed to generate FCM OAuth token: " . $e->getMessage(), ['exception' => $e]);
            return null;
        }
    }

    /**
     * Send push notification to a specific FCM device token
     */
    public function send(string $targetToken, string $title, string $body, array $data = []): bool
    {
        if (empty($targetToken)) {
            return false;
        }

        // Find the user (Student or Parent) by FCM token to check preferences
        $user = \App\Models\Student::where('fcm_token', $targetToken)->first()
            ?? \App\Models\StudentParent::where('fcm_token', $targetToken)->first();

        if ($user) {
            if (!$this->isNotificationEnabled($user, $data)) {
                Log::info("Notification suppressed because user turned off notifications for this category.", [
                    'user_id' => $user->id,
                    'user_type' => get_class($user),
                    'data' => $data
                ]);
                return false;
            }
        }

        if (empty($this->projectId)) {
            Log::error("FCM Project ID is not configured in .env (FIREBASE_PROJECT_ID).");
            return false;
        }

        $accessToken = $this->getAccessToken();
        if (!$accessToken) {
            return false;
        }

        $url = "https://fcm.googleapis.com/v1/projects/{$this->projectId}/messages:send";

        $messagePayload = [
            'token' => $targetToken,
            'notification' => [
                'title' => $title,
                'body' => $body,
            ],
            'webpush' => [
                'notification' => [
                    'icon' => '/images/turooa.png',
                    'badge' => '/images/turooa.png',
                ],
            ],
        ];

        // Only attach data if not empty (FCM v1 requires all data values to be strings)
        if (!empty($data)) {
            $stringData = [];
            foreach ($data as $key => $value) {
                $stringData[(string) $key] = (string) $value;
            }
            $messagePayload['data'] = $stringData;
        }

        try {
            $response = Http::withToken($accessToken)
                ->withHeaders([
                    'Content-Type' => 'application/json',
                ])
                ->post($url, ['message' => $messagePayload]);

            if ($response->successful()) {
                Log::info("FCM push notification sent successfully.", ['response' => $response->json()]);
                return true;
            } else {
                Log::error("FCM push notification failed.", [
                    'status' => $response->status(),
                    'body' => $response->json(),
                ]);
                return false;
            }
        } catch (\Exception $e) {
            Log::error("Exception occurred while sending FCM push notification: " . $e->getMessage(), ['exception' => $e]);
            return false;
        }
    }

    /**
     * Send push notification to a user model if they have a registered fcm_token
     */
    public function sendToUser($user, string $title, string $body, array $data = []): bool
    {
        if (!$user || empty($user->fcm_token)) {
            return false;
        }

        return $this->send($user->fcm_token, $title, $body, $data);
    }

    /**
     * Check if user enabled notification for this type/category
     */
    public function isNotificationEnabled($user, array $data): bool
    {
        $settings = $user->notification_settings;
        
        // If settings are not configured/empty, default to true for all notifications
        if (empty($settings)) {
            return true;
        }

        // 1. Mute Everything
        if (isset($settings['mute_all']) && $settings['mute_all']) {
            return false;
        }

        // Resolve notification category from data type
        $type = $data['type'] ?? '';

        if ($type === 'chat') {
            return true;
        }

        // Mapping:
        // - fee / fee_reminder -> fee_reminders
        // - homework / assignment -> assignment_alerts
        // - attendance -> attendance
        // - daily_update -> daily_updates
        // - announcement / events / others -> events_holidays
        $categoryKey = 'events_holidays'; // Default fallback

        if (in_array($type, ['fee', 'fee_reminder', 'payment'])) {
            $categoryKey = 'fee_reminders';
        } elseif (in_array($type, ['homework', 'homework_graded', 'assignment', 'homework_reminder'])) {
            $categoryKey = 'assignment_alerts';
        } elseif ($type === 'attendance') {
            $categoryKey = 'attendance';
        } elseif ($type === 'daily_update') {
            $categoryKey = 'daily_updates';
        }

        // If explicitly set, return the preference, else default to enabled (true)
        return filter_var($settings[$categoryKey] ?? true, FILTER_VALIDATE_BOOLEAN);
    }
}
