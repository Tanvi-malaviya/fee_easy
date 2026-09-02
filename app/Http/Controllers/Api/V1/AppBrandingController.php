<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\InstituteWhiteLabel;
use Illuminate\Http\Request;

class AppBrandingController extends Controller
{
    /**
     * Public, unauthenticated branding lookup for a single baked-in
     * institute_id — the app calls this at launch, before any user is
     * logged in, to decide whether to show its own default branding or
     * this institute's white-label branding.
     *
     * Deliberately returns `white_labeled: false` (200, not 404) for any
     * institute without an active + fully-submitted white-label record,
     * so the app can treat "not white-labeled" as a normal, cacheable
     * response rather than an error.
     */
    public function show(Request $request)
    {
        $request->validate([
            'institute_id' => 'required|integer',
        ]);

        $record = InstituteWhiteLabel::where('institute_id', $request->institute_id)
            ->where('status', InstituteWhiteLabel::STATUS_ACTIVE)
            ->first();

        if (!$record || !$record->branding_complete) {
            return response()->json([
                'status' => 'success',
                'data' => ['white_labeled' => false],
            ]);
        }

        return response()->json([
            'status' => 'success',
            'data' => [
                'white_labeled' => true,
                'app_name' => $record->app_name,
                'logo_url' => $record->app_logo_url,
                'primary_color' => $record->primary_color,
                'secondary_color' => $record->secondary_color,
            ],
        ]);
    }
}
