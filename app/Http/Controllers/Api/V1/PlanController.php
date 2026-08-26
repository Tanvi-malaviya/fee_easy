<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
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
        $whiteLabelPrice = (float) \App\Models\SystemSetting::get('mobile_app_whitelabel_price', 5000);
        $whiteLabelTitle = \App\Models\SystemSetting::get('mobile_app_whitelabel_title', 'Mobile App White Label');
        $whiteLabelBillingType = \App\Models\SystemSetting::get('mobile_app_whitelabel_billing_type', 'One Time');
        $whiteLabelEnabled = (bool) \App\Models\SystemSetting::get('mobile_app_whitelabel_enabled', true);
        $whiteLabelDesc = \App\Models\SystemSetting::get('mobile_app_whitelabel_description', 'Custom branded Android & iOS Mobile Application with your institute logo, colors, and name published on Google Play Store & Apple App Store.');

        $whiteLabelAddon = [
            'id' => 'mobile_app_whitelabel',
            'name' => $whiteLabelTitle,
            'title' => $whiteLabelTitle,
            'price' => $whiteLabelPrice,
            'billing_type' => $whiteLabelBillingType,
            'type' => 'one_time',
            'currency' => $currency,
            'formatted_price' => $currency . number_format($whiteLabelPrice),
            'description' => $whiteLabelDesc,
            'features' => [
                'Institute Name & Logo on Google Play Store & Apple App Store',
               
                'Push Notifications with Institute Branding',
              
            ],
            'is_active' => $whiteLabelEnabled,
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
