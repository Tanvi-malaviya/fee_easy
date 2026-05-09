<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\NoteCategory;
use Illuminate\Http\Request;

class NoteCategoryController extends Controller
{
    public function index() {
        return response()->json(['status' => 'success', 'data' => NoteCategory::all()]);
    }

    public function store(Request $request) {
        $category = NoteCategory::create($request->validate(['name' => 'required|string', 'color' => 'nullable|string']));
        return response()->json(['status' => 'success', 'data' => $category]);
    }
}
