<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeviceSession extends Model
{
    use HasFactory;

    protected $table = 'device_sessions';

    protected $fillable = [
        'institute_id',
        'token_id',
        'device',
        'os',
        'last_login',
        'last_open',
        'fcm_token',
    ];

    protected $casts = [
        'last_login' => 'datetime',
        'last_open' => 'datetime',
    ];

    public function institute()
    {
        return $this->belongsTo(Institute::class);
    }

    /**
     * Detect real device and OS from request.
     */
    public static function detect($request): array
    {
        // 1. Try body/query parameters
        $device = $request->input('device') ?: $request->input('device_name') ?: $request->input('device_model');
        $os = $request->input('os') ?: $request->input('device_os') ?: $request->input('os_version');

        // 2. Try common request headers (real values passed by mobile app/clients)
        if (empty($device)) {
            $device = $request->header('X-Device-Name') 
                ?: $request->header('X-Device-Model') 
                ?: $request->header('Device-Name') 
                ?: $request->header('Device-Model')
                ?: $request->header('X-Device-Brand');
        }

        if (empty($os)) {
            $os = $request->header('X-Device-OS') 
                ?: $request->header('X-OS-Version') 
                ?: $request->header('Device-OS') 
                ?: $request->header('OS-Version')
                ?: $request->header('X-OS')
                ?: $request->header('OS');
        }

        // 3. Fall back to parsing the User-Agent
        if (empty($device) || empty($os)) {
            $userAgent = $request->header('User-Agent');
            $detectedOs = 'Unknown OS';
            $detectedDevice = 'Unknown Device';

            if ($userAgent) {
                // OS detection
                if (preg_match('/iphone/i', $userAgent)) {
                    $detectedOs = 'iOS';
                    $detectedDevice = 'iPhone';
                } elseif (preg_match('/ipad/i', $userAgent)) {
                    $detectedOs = 'iOS';
                    $detectedDevice = 'iPad';
                } elseif (preg_match('/ipod/i', $userAgent)) {
                    $detectedOs = 'iOS';
                    $detectedDevice = 'iPod';
                } elseif (preg_match('/android/i', $userAgent)) {
                    $detectedOs = 'Android';
                    // Try to extract Android device model from User-Agent: e.g. Android 10; SM-A505F Build/QP1A.190711.020
                    if (preg_match('/android\s+[^;]+;\s+([^;)]+)/i', $userAgent, $matches)) {
                        $detectedDevice = trim($matches[1]);
                    } else {
                        $detectedDevice = 'Android Mobile';
                    }
                } elseif (preg_match('/windows/i', $userAgent)) {
                    $detectedOs = 'Windows';
                    $detectedDevice = 'Windows PC';
                } elseif (preg_match('/macintosh|mac os x/i', $userAgent)) {
                    $detectedOs = 'macOS';
                    $detectedDevice = 'Mac';
                } elseif (preg_match('/linux/i', $userAgent)) {
                    $detectedOs = 'Linux';
                    $detectedDevice = 'Linux PC';
                }
            }

            if (empty($device)) {
                $device = $detectedDevice;
            }
            if (empty($os)) {
                $os = $detectedOs;
            }
        }

        return [
            'device' => $device ?: 'Unknown Device',
            'os' => $os ?: 'Unknown OS',
        ];
    }
}
