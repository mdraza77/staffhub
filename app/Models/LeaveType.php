<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LeaveType extends Model
{
    protected $fillable = ['name', 'days_allowed', 'is_active'];
    use HasFactory;

    public function leaves()
    {
        return $this->hasMany(Leave::class);
    }
}
