<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DefectAttachment extends Model
{
    protected $fillable = ['defect_id', 'uploaded_by', 'file_name', 'file_path', 'file_type', 'file_size'];

    public function defect()
    {
        return $this->belongsTo(Defect::class)->withTrashed();
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by')->withTrashed();
    }
}
