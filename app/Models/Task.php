<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @method bool update(array $attributes = [], array $options = [])
 */
class Task extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'assigned_by',
        'assigned_to',
        'tester_id',
        'project_id',
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
    public function project()
    {
        return $this->belongsTo(Project::class)->withTrashed();
    }

    public function assigner()
    {
        return $this->belongsTo(User::class, 'assigned_by')->withTrashed();
    }

    public function engineer()
    {
        return $this->belongsTo(User::class, 'assigned_to')->withTrashed();
    }

    public function tester()
    {
        return $this->belongsTo(User::class, 'tester_id')->withTrashed();
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
