<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use App\Models\Activity;
use Illuminate\Http\Request;

class PlanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Plan::query();

        // Search Filter
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%");
        }

        // Status Filter
        if ($request->has('status') && !empty($request->status) && $request->status !== 'all') {
            $status = $request->status === 'active' ? 1 : 0;
            $query->where('status', $status);
        }

        $plans = $query->latest()->paginate(5);
        return view('plans.index', compact('plans'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('plans.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'duration_days' => 'required|integer|min:1',
            'trial_days' => 'required|integer|min:0',
            'status' => 'required|boolean',
        ]);

        Plan::create($validated);
        Activity::log("New subscription plan created: {$validated['name']}");
        return redirect()->route('plans.index')->with('success', 'Plan created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Plan $plan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Plan $plan)
    {
        return view('plans.edit', compact('plan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Plan $plan)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'duration_days' => 'required|integer|min:1',
            'trial_days' => 'required|integer|min:0',
            'status' => 'required|boolean',
        ]);

        $plan->update($validated);
        Activity::log("Subscription plan updated: {$plan->name}");
        return redirect()->route('plans.index')->with('success', 'Plan updated successfully.');
    }

    public function destroy(Plan $plan)
    {
        $name = $plan->name;
        $plan->delete();
        Activity::log("Subscription plan deleted: {$name}");
        return redirect()->route('plans.index')->with('success', 'Plan deleted successfully.');
    }

    /**
     * Update the status of the plan.
     */
    public function updateStatus(Request $request, Plan $plan)
    {
        $request->validate([
            'status' => 'required|boolean',
        ]);

        $plan->update([
            'status' => $request->status
        ]);

        $statusText = $request->status ? 'Active' : 'Inactive';
        Activity::log("Subscription plan status changed to {$statusText} for: {$plan->name}");

        return redirect()->back()->with('success', 'Plan status updated to ' . $statusText);
    }
}
