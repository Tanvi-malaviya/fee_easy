<?php

namespace App\Http\Controllers\Web\Institute;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DailyUpdateController extends Controller
{
    public function index()
    {
        return view('institute.updates.index');
    }
}
