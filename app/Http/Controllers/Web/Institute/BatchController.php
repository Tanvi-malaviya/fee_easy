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

    public function students($id)
    {
        $batch = \App\Models\Batch::where('id', $id)
            ->where('institute_id', auth('institute')->id())
            ->firstOrFail();
            
        $students = $batch->students;
        return view('institute.batches.students', compact('batch', 'students', 'id'));
    }

    public function homework($id)
    {
        $batch = \App\Models\Batch::where('id', $id)
            ->where('institute_id', auth('institute')->id())
            ->firstOrFail();
            
        $homeworks = $batch->homeworks;
        return view('institute.batches.homework', compact('batch', 'homeworks', 'id'));
    }

    public function attendance($id)
    {
        $batch = \App\Models\Batch::where('id', $id)
            ->where('institute_id', auth('institute')->id())
            ->firstOrFail();
            
        return view('institute.batches.attendance', compact('batch', 'id'));
    }

    public function resources($id)
    {
        $batch = \App\Models\Batch::where('id', $id)
            ->where('institute_id', auth('institute')->id())
            ->firstOrFail();
            
        return view('institute.batches.resources', compact('batch', 'id'));
    }

    public function homeworkShow($batchId, $homeworkId)
    {
        $batch = \App\Models\Batch::where('id', $batchId)
            ->where('institute_id', auth('institute')->id())
            ->firstOrFail();
            
        $homework = \App\Models\Homework::where('id', $homeworkId)
            ->where('batch_id', $batchId)
            ->firstOrFail();
            
        $id = $batchId;
        $homework_id = $homeworkId;
        return view('institute.batches.homework_show', compact('batch', 'homework', 'batchId', 'homeworkId', 'id', 'homework_id'));
    }
}
