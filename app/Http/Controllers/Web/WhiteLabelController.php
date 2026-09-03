<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\InstituteWhiteLabel;
use Illuminate\Http\Request;

class WhiteLabelController extends Controller
{
    /**
     * List institutes that have purchased the White Label add-on, so ops
     * can review submitted branding before building/publishing their app.
     */
    public function index(Request $request)
    {
        $query = InstituteWhiteLabel::with('institute')->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $records = $query->paginate(15)->withQueryString();

        return view('whitelabel.index', compact('records'));
    }

    /**
     * Mark an institute's submitted branding as confirmed — signals to ops
     * that it's ready to build and submit to the app stores. Does not
     * trigger any build/deploy step itself.
     */
    public function confirm(InstituteWhiteLabel $whiteLabel, Request $request)
    {
        $validated = $request->validate([
            'admin_notes' => 'nullable|string|max:1000',
            'android_package_id' => 'nullable|string|max:255',
            'ios_bundle_id' => 'nullable|string|max:255',
        ]);

        $whiteLabel->update(array_merge($validated, [
            'admin_confirmed_at' => now(),
        ]));

        Activity::log("White Label branding confirmed for institute #{$whiteLabel->institute_id}");

        return redirect()->route('whitelabel.index')->with('success', 'Branding confirmed.');
    }
}
