<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    protected $fillable = [
        'institute_id', 'expense_category_id', 'amount', 'date', 'description', 'receipt_image', 'payment_method'
    ];

    public function institute()
    {
        return $this->belongsTo(Institute::class);
    }

    public function category()
    {
        return $this->belongsTo(ExpenseCategory::class, 'expense_category_id');
    }

    public function getReceiptImageAttribute($value)
    {
        if (!$value) {
            return null;
        }
        return url('storage/' . $value);
    }
}
