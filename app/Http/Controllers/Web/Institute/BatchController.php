<?php

namespace App\Http\Controllers\Web\Institute;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BatchController extends Controller
{
    public function index()
    {
        return view('institute.batches.index');
    }

    public function create()
    {
        return view('institute.batches.create');
    }

    public function show($id)
    {
        return view('institute.batches.show', compact('id'));
    }

    public function edit($id)
    {
        return view('institute.batches.edit', compact('id'));
    }
}
