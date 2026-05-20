<?php

namespace App\Http\Controllers\Web\Institute;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        return view('institute.notifications.index');
    }

    public function compose()
    {
        return view('institute.notifications.push');
    }
}
