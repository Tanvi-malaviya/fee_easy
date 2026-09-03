<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\User;
use Illuminate\Http\Request;

class ActivityController extends Controller
{
    /**
     * Display a listing of system activities.
     */
    public function index(Request $request)
    {
        $query = Activity::with('user');

        if ($request->filled('search')) {
            $query->where('activity', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $activities = $query->latest()->paginate(50)->appends($request->query());

        $actors = User::orderBy('name')->get(['id', 'name']);

        return view('activity.index', compact('activities', 'actors'));
    }
}
