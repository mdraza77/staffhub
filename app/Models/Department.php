<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable(['name', 'slug', 'description', 'created_at', 'updated_at', 'deleted_at'])]
class Department extends Model
{
    use SoftDeletes;
    public function users()
    {
        return $this->hasMany(User::class);
    }
}
