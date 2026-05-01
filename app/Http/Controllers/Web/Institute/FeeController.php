<?php

namespace App\Http\Controllers\Web\Institute;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FeeController extends Controller
{
    public function index()
    {
        return view('institute.fees.index');
    }

    public function collect()
    {
        return view('institute.fees.collect');
    }

    public function showReceipt($id)
    {
        return view('institute.fees.receipts.show', compact('id'));
    }
}
