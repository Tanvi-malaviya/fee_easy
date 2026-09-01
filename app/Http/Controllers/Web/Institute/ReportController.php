<?php

namespace App\Http\Controllers\Web\Institute;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index()
    {
        $institute = \Illuminate\Support\Facades\Auth::guard('institute')->user();
        $batches = \App\Models\Batch::with(['students' => function($q) {
            $q->select('id', 'name', 'enrollment_id', 'batch_id', 'standard')->orderBy('name');
        }])->where('institute_id', $institute->id)->get();
        return view('institute.reports.index', compact('batches'));
    }
}
