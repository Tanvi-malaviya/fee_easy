<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\SystemSetting;
use Illuminate\Http\JsonResponse;

class SystemVersionController extends Controller
{
    /**
     * Get system version details.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'data' => [
                'app_version' => SystemSetting::get('app_version', '1.0.0'),
                'web_version' => SystemSetting::get('web_version', '1.0.0'),
                'student_app_version' => SystemSetting::get('student_app_version', '1.0.0'),
            ]
        ]);
    }
}
