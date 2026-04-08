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
            'institute_name' => 'nullable|string|max:255',
            'email' => 'required|email|unique:institutes,email|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'pincode' => 'nullable|string|max:10',
            'status' => 'required|string',
            'logo' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('logo')) {
            $validated['logo'] = $request->file('logo')->store('institutes/logos', 'public');
        }

        $institute = Institute::create($validated);

        // Create Default 14-day Trial Subscription
        Subscription::create([
            'institute_id' => $institute->id,
            'plan_name' => 'Free Trial',
            'amount' => 0,
            'start_date' => now(),
            'end_date' => now()->addDays(14),
            'status' => 'trial',
        ]);

        Activity::log("New institute registered: {$institute->institute_name}");

        return redirect()->route('institutes.index')->with('success', 'Institute created successfully with 14-day free trial.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Institute $institute)
    {
        // Show logic
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
            'institute_name' => 'nullable|string|max:255',
            'email' => 'required|email|unique:institutes,email,' . $institute->id . '|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'pincode' => 'nullable|string|max:10',
            'status' => 'required|string',
            'logo' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('logo')) {
            // Delete old logo if exists
            if ($institute->logo && \Storage::disk('public')->exists($institute->logo)) {
                \Storage::disk('public')->delete($institute->logo);
            }
            $validated['logo'] = $request->file('logo')->store('institutes/logos', 'public');
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
}
