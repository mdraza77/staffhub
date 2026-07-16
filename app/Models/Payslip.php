<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payslip extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'month',
        'year',
        'working_days',
        'present_days',
        'paid_leaves',
        'unpaid_leaves',
        'gross_salary',
        'total_deductions',
        'net_salary',
        'status'
    ];

    // Relation with User
    public function user()
    {
        return $this->belongsTo(User::class)->withTrashed();
    }
}
