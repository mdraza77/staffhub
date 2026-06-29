<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['user_id', 'date', 'check_in_time', 'check_out_time', 'status', 'note', 'created_at', 'updated_at'])]
class Attendance extends Model
{
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
