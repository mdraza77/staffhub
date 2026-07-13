<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeBreak extends Model
{
    protected $fillable = [
        'user_id',
        'break_type_id',
        'started_at',
        'expected_end_time',
        'ended_at',
        'status',
        'remark'
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'expected_end_time' => 'datetime',
        'ended_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function breakType()
    {
        return $this->belongsTo(BreakType::class, 'break_type_id');
    }
}
