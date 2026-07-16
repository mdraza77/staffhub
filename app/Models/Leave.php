<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Leave extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'leave_type_id',
        'start_date',
        'end_date',
        'total_days',
        'reason',
        'status',
        'admin_remark'
    ];
    public function user()
    {
        return $this->belongsTo(User::class)->withTrashed();
    }

    // Yeh leave request kis type ki chhutti ke liye hai?
    public function leaveType()
    {
        return $this->belongsTo(LeaveType::class)->withTrashed();
    }
}
