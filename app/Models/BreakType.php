<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BreakType extends Model
{
    protected $fillable = ['name', 'duration_minutes', 'icon', 'is_active'];

    public function employeeBreaks()
    {
        return $this->hasMany(EmployeeBreak::class);
    }
}
