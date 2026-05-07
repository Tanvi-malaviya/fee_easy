<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NoteChecklist extends Model
{
    use HasFactory;
    protected $fillable = ['note_id', 'title', 'is_completed'];
    protected $casts = ['is_completed' => 'boolean'];
    public function note() { return $this->belongsTo(Note::class); }
}
