<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Note extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id', 
        'institute_id', 
        'notable_id', 
        'notable_type', 
        'category_id', 
        'category', 
        'title', 
        'slug', 
        'content', 
        'cover_image', 
        'is_bookmarked', 
        'is_archived'
    ];

    protected $hidden = [
        'cover_image', 
        'slug', 
        'user_id', 
        'institute_id', 
        'notable_id', 
        'notable_type', 
        'category_id',
        'is_archived', 
        'deleted_at',
        'checklists',
        'images',
        'category_relation'
    ];

    protected $casts = [
        'is_bookmarked' => 'boolean',
        'is_archived' => 'boolean',
    ];

    protected $appends = ['image_url'];

    public function getImageUrlAttribute()
    {
        return $this->cover_image ? url('storage/' . $this->cover_image) : null;
    }

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($note) {
            $note->slug = Str::slug($note->title) . '-' . Str::random(5);
        });
    }

    public function user() { return $this->belongsTo(User::class); }
    public function institute() { return $this->belongsTo(Institute::class); }
    public function notable() { return $this->morphTo(); }
    public function category_relation() { return $this->belongsTo(NoteCategory::class, 'category_id'); }
    public function checklists() { return $this->hasMany(NoteChecklist::class); }
    public function images() { return $this->hasMany(NoteImage::class); }
}
