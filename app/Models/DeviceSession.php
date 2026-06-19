<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

class DeviceSession extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'device_sessions';

    protected $fillable = [
        'institute_id',
        'token_id',
        'session_id',
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
                // Determine browser name
                $browser = 'Web Browser';
                if (preg_match('/edg/i', $userAgent)) {
                    $browser = 'Edge';
                } elseif (preg_match('/chrome|crios/i', $userAgent)) {
                    $browser = 'Chrome';
                } elseif (preg_match('/firefox|fxios/i', $userAgent)) {
                    $browser = 'Firefox';
                } elseif (preg_match('/safari/i', $userAgent) && !preg_match('/chrome|crios/i', $userAgent)) {
                    $browser = 'Safari';
                }

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
                    // Try to extract Android device model from User-Agent
                    if (preg_match('/android\s+[^;]+;\s+([^;)]+)/i', $userAgent, $matches)) {
                        $detectedDevice = trim($matches[1]);
                    } else {
                        $detectedDevice = 'Android Mobile';
                    }
                } elseif (preg_match('/windows/i', $userAgent)) {
                    $detectedOs = 'Windows';
                    $detectedDevice = "{$browser} on Windows";
                } elseif (preg_match('/macintosh|mac os x/i', $userAgent)) {
                    $detectedOs = 'macOS';
                    $detectedDevice = "{$browser} on Mac";
                } elseif (preg_match('/linux/i', $userAgent)) {
                    $detectedOs = 'Linux';
                    $detectedDevice = "{$browser} on Linux";
                }
            }

            if (empty($device)) {
                $device = $detectedDevice;
            }
            if (empty($os)) {
                $os = $detectedOs;
            }
        }

        $sessionId = null;
        if ($request->hasSession()) {
            $sessionId = $request->session()->getId();
        } else {
            $sessionId = $request->input('device_id') 
                ?: $request->input('uuid') 
                ?: $request->header('X-Device-ID') 
                ?: $request->header('Device-ID') 
                ?: $request->header('X-Session-ID');
        }

        return [
            'device' => $device ?: 'Unknown Device',
            'os' => $os ?: 'Unknown OS',
            'session_id' => $sessionId,
        ];
    }
}
