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

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($session) {
            $tokenId = $session->token_id;
            if ($tokenId) {
                \DB::table('personal_access_tokens')
                    ->where('id', $tokenId)
                    ->orWhere('name', "refresh_token_for_{$tokenId}")
                    ->delete();
            }
        });
    }

    /**
     * Terminate the device session by deleting associated Sanctum tokens and deleting itself.
     */
    public function terminate()
    {
        $tokenId = $this->token_id;
        if ($tokenId) {
            \DB::table('personal_access_tokens')
                ->where('id', $tokenId)
                ->orWhere('name', "refresh_token_for_{$tokenId}")
                ->delete();
        }

        $this->token_id = null;
        $this->save();
        $this->delete();
    }

    /**
     * Prune sessions whose linked access token has expired or been deleted.
     * Call this periodically or after login to prevent ghost sessions.
     *
     * @param int|null $instituteId  Only prune sessions for this institute (null = all)
     */
    public static function pruneExpired(?int $instituteId = null): int
    {
        $query = self::whereNotNull('token_id');
        if ($instituteId !== null) {
            $query->where('institute_id', $instituteId);
        }

        $sessions = $query->get();
        $pruned = 0;

        foreach ($sessions as $session) {
            $token = \DB::table('personal_access_tokens')
                ->where('id', $session->token_id)
                ->first();

            // Delete the session if the token no longer exists or has expired
            $tokenMissing = !$token;
            $tokenExpired = $token && $token->expires_at && \Carbon\Carbon::parse($token->expires_at)->isPast();

            if ($tokenMissing || $tokenExpired) {
                // Also remove the expired token row if present
                if ($token) {
                    \DB::table('personal_access_tokens')
                        ->where('id', $session->token_id)
                        ->orWhere('name', "refresh_token_for_{$session->token_id}")
                        ->delete();
                }
                $session->delete();
                $pruned++;
            }
        }

        return $pruned;
    }

    /**
     * Find a matching device session for a user and request.
     */
    public static function findSessionForUser($user, \Illuminate\Http\Request $request, $currentToken = null): ?self
    {
        // 1. Try matching by currentToken token_id if available
        $resolvedTokenId = null;
        if ($currentToken && !($currentToken instanceof \Laravel\Sanctum\TransientToken)) {
            $resolvedTokenId = $currentToken->id;

            // If it is a refresh token, extract the linked access token ID from its name
            if (preg_match('/^refresh_token_for_(\d+)$/', $currentToken->name, $matches)) {
                $resolvedTokenId = (int)$matches[1];
            }

            $session = self::where('token_id', $resolvedTokenId)->first();
            if ($session) {
                return $session;
            }
        }

        // 2. Detect request properties
        $detection = self::detect($request);
        $device = $detection['device'];
        $os = $detection['os'];
        $sessionId = $detection['session_id'];
        $fcmToken = $request->input('fcm_token') ?: $request->input('fcm-token') ?: $request->input('fcm_device_token') ?: $request->header('X-FCM-Token') ?: $request->header('FCM-Token');

        // 3. Try matching by FCM token
        if (!empty($fcmToken)) {
            $session = $user->deviceSessions()->where('fcm_token', $fcmToken)->first();
            if ($session) {
                return $session;
            }
        }

        // 4. Try matching by session_id
        if (!empty($sessionId)) {
            $session = $user->deviceSessions()->where('session_id', $sessionId)->first();
            if ($session) {
                return $session;
            }
        }

        // 5. Try matching by device and OS (preferring session_id null or any matching)
        if ($device !== 'Unknown Device' && $os !== 'Unknown OS') {
            // First try with whereNull to find exact web session
            $session = $user->deviceSessions()
                ->where('device', $device)
                ->where('os', $os)
                ->whereNull('session_id')
                ->first();

            if ($session) {
                return $session;
            }

            // Fallback to any session on this device/os
            $session = $user->deviceSessions()
                ->where('device', $device)
                ->where('os', $os)
                ->first();

            if ($session) {
                return $session;
            }
        }

        // 6. Last resort: if we know the current token ID, look for any active app session
        //    belonging to this user that still references that token. This catches cases where
        //    device / OS info is missing ("Unknown Device / Unknown OS") and there is no
        //    FCM token or session_id to match on.
        if ($resolvedTokenId) {
            $session = self::whereNotNull('token_id')
                ->where('token_id', $resolvedTokenId)
                ->first();

            if ($session) {
                return $session;
            }
        }

        return null;
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
