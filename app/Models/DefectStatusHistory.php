<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DefectStatusHistory extends Model
{
    protected $fillable = ['defect_id', 'changed_by', 'old_status', 'new_status', 'remark'];

    public function defect()
    {
        return $this->belongsTo(Defect::class)->withTrashed();
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'changed_by')->withTrashed();
    }
}
