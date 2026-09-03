<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\AddOn;
use App\Models\Plan;
use Illuminate\Http\Request;

class PlanController extends Controller
{
    /**
     * Get all active plans.
     */
    public function index()
    {
        $plans = Plan::where('status', true)
            ->where('price', '>', 0)
            ->where('name', 'not like', '%free%')
            ->orderBy('price', 'asc')
            ->get();

        $currency = \App\Models\SystemSetting::get('currency_symbol', '₹');
        $whiteLabel = AddOn::whiteLabel();

        $whiteLabelAddon = [
            'id' => AddOn::SLUG_WHITE_LABEL,
            'name' => $whiteLabel?->name ?? 'Mobile App White Label',
            'title' => $whiteLabel?->name ?? 'Mobile App White Label',
            'price' => $whiteLabel?->price ?? 5000,
            'billing_type' => $whiteLabel?->billing_type ?? 'One Time',
            'type' => 'one_time',
            'currency' => $currency,
            'formatted_price' => $whiteLabel?->formatted_price ?? ($currency . '5,000'),
            'description' => $whiteLabel?->description ?? '',
            'features' => $whiteLabel?->features ?: [
                'Institute Name & Logo on Google Play Store & Apple App Store',
                'Push Notifications with Institute Branding',
            ],
            'is_active' => $whiteLabel?->enabled ?? false,
        ];

        return response()->json([
            'status' => 'success',
            'data' => $plans,
            'addons' => [
                $whiteLabelAddon
            ],
            'white_label_addon' => $whiteLabelAddon
        ]);
    }
}
