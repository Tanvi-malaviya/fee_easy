<?php

namespace App\Http\Controllers\Web\Institute;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function index()
    {
        return view('institute.attendance.index');
    }

    public function create()
    {
        return view('institute.attendance.create');
    }

    public function show($id)
    {
        return view('institute.attendance.show', compact('id'));
    }

    public function edit($id)
    {
        return view('institute.attendance.edit', compact('id'));
    }
}
