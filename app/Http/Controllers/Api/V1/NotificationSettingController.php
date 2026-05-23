<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class NotificationSettingController extends Controller
{
    /**
     * Get notification settings.
     */
    public function getSettings(Request $request): JsonResponse
    {
        $user = $request->user();
        if (!$user) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        $defaultSettings = [
            'mute_all' => false,
            'fee_reminders' => true,
            'assignment_alerts' => true,
            'attendance' => true,
            'daily_updates' => true,
            'events_holidays' => true,
        ];

        $currentSettings = $user->notification_settings ?? [];

        // Merge defaults with current settings
        $settings = array_merge($defaultSettings, $currentSettings);

        // Ensure proper boolean types
        foreach ($settings as $key => $value) {
            $settings[$key] = filter_var($value, FILTER_VALIDATE_BOOLEAN);
        }

        // Enforce relationships
        if ($settings['mute_all']) {
            $settings['fee_reminders'] = false;
            $settings['assignment_alerts'] = false;
            $settings['attendance'] = false;
            $settings['daily_updates'] = false;
            $settings['events_holidays'] = false;
        } else {
            $hasAnyActive = $settings['fee_reminders'] ||
                            $settings['assignment_alerts'] ||
                            $settings['attendance'] ||
                            $settings['daily_updates'] ||
                            $settings['events_holidays'];

            if (!$hasAnyActive) {
                $settings['mute_all'] = true;
            }
        }

        return response()->json([
            'status' => 'success',
            'data' => $settings
        ]);
    }

    /**
     * Update notification settings.
     */
    public function updateSettings(Request $request): JsonResponse
    {
        $user = $request->user();
        if (!$user) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        $request->validate([
            'mute_all' => 'sometimes|boolean',
            'fee_reminders' => 'sometimes|boolean',
            'assignment_alerts' => 'sometimes|boolean',
            'attendance' => 'sometimes|boolean',
            'daily_updates' => 'sometimes|boolean',
            'events_holidays' => 'sometimes|boolean',
        ]);

        $defaultSettings = [
            'mute_all' => false,
            'fee_reminders' => true,
            'assignment_alerts' => true,
            'attendance' => true,
            'daily_updates' => true,
            'events_holidays' => true,
        ];

        $currentSettings = $user->notification_settings ?? [];
        $merged = array_merge($defaultSettings, $currentSettings);

        // Update with request inputs
        foreach ($defaultSettings as $key => $default) {
            if ($request->has($key)) {
                $merged[$key] = filter_var($request->input($key), FILTER_VALIDATE_BOOLEAN);
            } else {
                $merged[$key] = filter_var($merged[$key] ?? $default, FILTER_VALIDATE_BOOLEAN);
            }
        }

        // Handle transitioning out of "mute_all" state:
        $wasMuted = isset($currentSettings['mute_all']) ? filter_var($currentSettings['mute_all'], FILTER_VALIDATE_BOOLEAN) : false;
        
        $isChangingCategories = false;
        foreach (['fee_reminders', 'assignment_alerts', 'attendance', 'daily_updates', 'events_holidays'] as $cat) {
            if ($request->has($cat)) {
                $isChangingCategories = true;
                break;
            }
        }

        $explicitMuteAll = $request->has('mute_all') ? filter_var($request->input('mute_all'), FILTER_VALIDATE_BOOLEAN) : null;

        // If currently muted, and we explicitly turn off mute_all, OR we update any individual category toggle:
        if ($wasMuted && ($explicitMuteAll === false || $isChangingCategories)) {
            $merged['mute_all'] = false;
            foreach (['fee_reminders', 'assignment_alerts', 'attendance', 'daily_updates', 'events_holidays'] as $cat) {
                if (!$request->has($cat)) {
                    $merged[$cat] = true; // Restore defaults
                }
            }
        }

        // Check if request is explicitly enabling any category
        $enablingCategory = false;
        foreach (['fee_reminders', 'assignment_alerts', 'attendance', 'daily_updates', 'events_holidays'] as $cat) {
            if ($request->has($cat) && filter_var($request->input($cat), FILTER_VALIDATE_BOOLEAN) === true) {
                $enablingCategory = true;
                break;
            }
        }

        if ($enablingCategory && filter_var($request->input('mute_all'), FILTER_VALIDATE_BOOLEAN) !== true) {
            $merged['mute_all'] = false;
        }

        // Enforce toggle relationships:
        // Rule 1: If mute_all is true, all other category toggles must be set to false.
        if ($merged['mute_all']) {
            $merged['fee_reminders'] = false;
            $merged['assignment_alerts'] = false;
            $merged['attendance'] = false;
            $merged['daily_updates'] = false;
            $merged['events_holidays'] = false;
        } else {
            // Rule 2: If any individual category toggle is set to true, mute_all must be set to false.
            $hasAnyActive = $merged['fee_reminders'] ||
                            $merged['assignment_alerts'] ||
                            $merged['attendance'] ||
                            $merged['daily_updates'] ||
                            $merged['events_holidays'];

            if ($hasAnyActive) {
                $merged['mute_all'] = false;
            } else {
                // Rule 3: If all category toggles are false, mute_all automatically becomes true.
                $merged['mute_all'] = true;
            }
        }

        // Ensure all values are proper booleans in the array
        foreach ($merged as $key => $val) {
            $merged[$key] = filter_var($val, FILTER_VALIDATE_BOOLEAN);
        }

        $user->notification_settings = $merged;
        $user->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Notification preferences updated successfully.',
            'data' => $user->notification_settings
        ]);
    }
}
