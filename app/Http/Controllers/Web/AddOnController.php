<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\AddOn;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AddOnController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = AddOn::query();

        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%");
        }

        if ($request->has('status') && !empty($request->status) && $request->status !== 'all') {
            $query->where('enabled', $request->status === 'active');
        }

        $addOns = $query->latest()->paginate(10);

        return view('addons.index', compact('addOns'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $this->validated($request);
        $validated['slug'] = $this->uniqueSlug($validated['name']);
        $validated['enabled'] = $request->boolean('enabled');

        $addOn = AddOn::create($validated);
        Activity::log("New add-on created: {$addOn->name}");

        return redirect()->route('addons.index')->with('success', 'Add-on created successfully.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, AddOn $addOn)
    {
        $validated = $this->validated($request, $addOn);
        $validated['enabled'] = $request->boolean('enabled');
        // Slug and kind are permanent once created — the purchase/activation
        // code for an add-on is written against a specific slug+kind pairing
        // (e.g. InstituteWhiteLabelController expects AddOn::SLUG_WHITE_LABEL
        // to always be kind=custom), so changing either after the fact would
        // silently break that wiring.
        unset($validated['kind']);

        $addOn->update($validated);
        Activity::log("Add-on updated: {$addOn->name}");

        return redirect()->route('addons.index')->with('success', 'Add-on updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AddOn $addOn)
    {
        if ($addOn->kind === AddOn::KIND_CUSTOM) {
            return redirect()->route('addons.index')
                ->with('error', 'Custom add-ons have dedicated backend code and cannot be deleted here.');
        }

        if ($addOn->purchases()->exists()) {
            return redirect()->route('addons.index')
                ->with('error', 'This add-on has existing purchases and cannot be deleted. Disable it instead.');
        }

        $name = $addOn->name;
        $addOn->delete();
        Activity::log("Add-on deleted: {$name}");

        return redirect()->route('addons.index')->with('success', 'Add-on deleted successfully.');
    }

    /**
     * Quick enable/disable toggle.
     */
    public function updateStatus(Request $request, AddOn $addOn)
    {
        $request->validate(['enabled' => 'required|boolean']);

        $addOn->update(['enabled' => $request->boolean('enabled')]);

        $statusText = $addOn->enabled ? 'Enabled' : 'Disabled';
        Activity::log("Add-on status changed to {$statusText} for: {$addOn->name}");

        return redirect()->back()->with('success', "Add-on {$statusText}.");
    }

    private function validated(Request $request, ?AddOn $addOn = null): array
    {
        $kindRule = $addOn ? 'nullable' : 'required|in:flag,quota,custom';

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'price' => 'required|numeric|min:0|max:999999',
            'billing_type' => 'nullable|string|max:100',
            'kind' => $kindRule,
            'quota_key' => 'nullable|string|max:100',
            'quota_value' => 'nullable|numeric|min:0',
        ]);

        $validated['billing_type'] = $validated['billing_type'] ?? 'One Time';

        return $validated;
    }

    private function uniqueSlug(string $name): string
    {
        $base = Str::slug($name, '_');
        $slug = $base;
        $i = 1;
        while (AddOn::where('slug', $slug)->exists()) {
            $slug = "{$base}_{$i}";
            $i++;
        }
        return $slug;
    }
}
