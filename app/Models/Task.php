<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'assigned_by',
        'assigned_to',
        'tester_id',
        'project_name',
        'title',
        'description',
        'deadline',
        'priority',
        'status',
    ];

    protected $casts = [
        'deadline' => 'date',
    ];

    // Relationships
    public function assigner()
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }

    public function engineer()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function tester()
    {
        return $this->belongsTo(User::class, 'tester_id');
    }

    public function comments()
    {
        return $this->hasMany(TaskComment::class);
    }

    public function documents()
    {
        return $this->hasMany(TaskDocument::class);
    }

    public function statusHistories()
    {
        return $this->hasMany(TaskStatusHistory::class);
    }
}
