<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use Illuminate\Http\Request;

class ActivityController extends Controller
{
    /**
     * Display a listing of system activities.
     */
    public function index()
    {
        $activities = Activity::with('user')->latest()->paginate(50);
        return view('activity.index', compact('activities'));
    }
}
