<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['name', 'slug', 'description'])]
class Department extends Model
{
    public function users()
    {
        return $this->hasMany(User::class);
    }
}
