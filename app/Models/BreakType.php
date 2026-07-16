<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BreakType extends Model
{
    protected $fillable = ['name', 'duration_minutes', 'icon', 'is_active'];

    use SoftDeletes;

    public function employeeBreaks()
    {
        return $this->hasMany(EmployeeBreak::class);
    }
}
