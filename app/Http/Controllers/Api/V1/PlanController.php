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
        $plans = Plan::where('status', 'active')->orderBy('price', 'asc')->get();
        
        return response()->json([
            'status' => 'success',
            'data' => $plans
        ]);
    }
}
