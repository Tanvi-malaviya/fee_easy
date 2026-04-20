<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExpenseCategory extends Model
{
    protected $fillable = [
        'institute_id', 'name'
    ];

    public function institute()
    {
        return $this->belongsTo(Institute::class);
    }

    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }
}
