<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Defect extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'defect_id', 'project_name', 'title', 'description', 'steps_to_reproduce',
        'module', 'sub_module', 'environment', 'browser_os',
        'severity', 'priority', 'status', 'reported_by', 'assigned_to'
    ];

    // Auto-generate Bug ID
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($defect) {
            $latest = self::latest()->first();
            $number = $latest ? ((int) substr($latest->defect_id, -3)) + 1 : 1;
            // Generates: BUG-2026-001
            $defect->defect_id = 'BUG-' . date('Y') . '-' . str_pad($number, 3, '0', STR_PAD_LEFT);
        });
    }

    // Connect everything up!
    public function reporter()
    {
        return $this->belongsTo(User::class, 'reported_by');
    }

    public function assignee()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    // One Defect has Many Attachments
    public function attachments()
    {
        return $this->hasMany(DefectAttachment::class);
    }

    // One Defect has Many Status Histories
    public function histories()
    {
        return $this->hasMany(DefectStatusHistory::class)->latest();
    }
}
