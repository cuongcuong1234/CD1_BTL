<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Teacher extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'teacher_code',
        'name',
        'email',
        'phone',
        'specialization',
        'status',
    ];

    protected $casts = [
        'status' => 'string',
    ];

    public function classrooms()
    {
        return $this->hasMany(Classroom::class);
    }
}
