<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NoteImage extends Model
{
    use HasFactory;
    protected $fillable = ['note_id', 'image'];
    protected $appends = ['url'];
    public function getUrlAttribute() { return url('storage/' . $this->image); }
    public function note() { return $this->belongsTo(Note::class); }
}
