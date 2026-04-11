<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Institute;
use App\Models\Subscription;
use App\Models\Activity;
use Illuminate\Http\Request;
use Carbon\Carbon;

class InstituteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Institute::query();

        // Search Filter
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('institute_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // Status Filter
        if ($request->has('status') && !empty($request->status) && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        $institutes = $query->latest()->paginate(10);
        return view('institutes.index', compact('institutes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('institutes.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'institute_name' => 'required|string|max:255',
            'email' => 'required|email|unique:institutes,email|max:255',
            'phone' => 'required|string|regex:/^[0-9]{10}$/',
            'address' => 'required|string',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'pincode' => 'required|string|regex:/^[0-9]{6}$/',
            'status' => 'required|string|in:active,inactive,suspended',
            'logo' => 'nullable|image|max:2048',
        ], [
            'phone.regex' => 'The phone number must be exactly 10 digits.',
            'pincode.regex' => 'The pincode must be exactly 6 digits.',
        ]);

        $validated['logo'] = $this->handleLogo($request);

        $institute = Institute::create($validated);
        $trialDays = \App\Models\SystemSetting::get('default_trial_days', 14);

        // Create Default Trial Subscription
        Subscription::create([
            'institute_id' => $institute->id,
            'plan_name' => 'Free Trial',
            'amount' => 0,
            'start_date' => now(),
            'end_date' => now()->addDays($trialDays),
            'status' => 'trial',
        ]);

        Activity::log("New institute registered: {$institute->institute_name}");

        return redirect()->route('institutes.index')->with('success', "Institute created successfully with {$trialDays}-day free trial.");
    }

    /**
     * Display the specified resource.
     */
    public function show(Institute $institute)
    {
        $institute->load(['subscriptions' => function($q) {
            $q->latest();
        }, 'whatsappSettings']);

        $stats = [
            'students_count' => $institute->students()->count(),
            'batches_count' => $institute->batches()->count(),
            'active_subscription' => $institute->subscriptions()->where('status', 'active')->first() ?? $institute->subscriptions()->where('status', 'trial')->first(),
        ];

        return view('institutes.show', compact('institute', 'stats'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Institute $institute)
    {
        return view('institutes.edit', compact('institute'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Institute $institute)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'institute_name' => 'required|string|max:255',
            'email' => 'required|email|unique:institutes,email,' . $institute->id . '|max:255',
            'phone' => 'required|string|regex:/^[0-9]{10}$/',
            'address' => 'required|string',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'pincode' => 'required|string|regex:/^[0-9]{6}$/',
            'status' => 'required|string|in:active,inactive,suspended,blocked',
            'logo' => 'nullable|image|max:2048',
        ], [
            'phone.regex' => 'The phone number must be exactly 10 digits.',
            'pincode.regex' => 'The pincode must be exactly 6 digits.',
        ]);

        $newLogo = $this->handleLogo($request, $institute->logo);
        if ($newLogo) {
            $validated['logo'] = $newLogo;
        }

        $institute->update($validated);

        Activity::log("Institute updated: {$institute->institute_name}");

        return redirect()->route('institutes.index')->with('success', 'Institute updated successfully.');
    }

    public function destroy(Institute $institute)
    {
        $name = $institute->institute_name;
        $institute->delete();
        Activity::log("Institute record deleted: {$name}");
        return redirect()->route('institutes.index')->with('success', 'Institute deleted successfully.');
    }

    /**
     * Update the status of the institute.
     */
    public function updateStatus(Request $request, Institute $institute)
    {
        $request->validate([
            'status' => 'required|string|in:active,inactive,suspended,blocked',
        ]);

        $institute->update([
            'status' => $request->status
        ]);

        Activity::log("Institute status changed to {$request->status} for: {$institute->institute_name}");

        return redirect()->back()->with('success', 'Institute status updated to ' . ucfirst($request->status));
    }

    /**
     * Handle Logo Upload (Support for file and base64 fallback)
     */
    private function handleLogo(Request $request, $existingLogo = null)
    {
        // 1. Check for real file upload first
        if ($request->hasFile('logo')) {
            if ($existingLogo && \Storage::disk('public')->exists($existingLogo)) {
                \Storage::disk('public')->delete($existingLogo);
            }
            return $request->file('logo')->store('institutes/logos', 'public');
        }

        // 2. Fallback to base64 persistence if file is missing (e.g. after validation error)
        if ($request->has('logo_base64') && !empty($request->logo_base64)) {
            try {
                $base64Data = $request->logo_base64;
                
                // Extract image data
                if (preg_match('/^data:image\/(\w+);base64,/', $base64Data, $type)) {
                    $data = substr($base64Data, strpos($base64Data, ',') + 1);
                    $type = strtolower($type[1]); // jpg, png, etc

                    if (!in_array($type, ['jpg', 'jpeg', 'gif', 'png', 'webp'])) {
                        return $existingLogo;
                    }

                    $data = base64_decode($data);
                    if ($data === false) return $existingLogo;

                    // Delete old logo if replacing with base64 recovery
                    if ($existingLogo && \Storage::disk('public')->exists($existingLogo)) {
                        \Storage::disk('public')->delete($existingLogo);
                    }

                    $fileName = 'institutes/logos/' . uniqid() . '.' . $type;
                    \Storage::disk('public')->put($fileName, $data);
                    
                    return $fileName;
                }
            } catch (\Exception $e) {
                // Silently fail and return existing or null
            }
        }

        return $existingLogo;
    }
}
