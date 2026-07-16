<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LeaveType extends Model
{
    protected $fillable = ['name', 'days_allowed', 'is_active'];

    use HasFactory, SoftDeletes;

    public function leaves()
    {
        return $this->hasMany(Leave::class);
    }
}
