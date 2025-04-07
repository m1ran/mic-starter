<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'date',
        'comment',
        'total_amount',
    ];

    public function items()
    {
        return $this->hasMany(ExpenseItem::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
