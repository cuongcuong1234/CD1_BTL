<?php

namespace App\Events;

use App\Models\Student;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class StudentUpdated
{
    use Dispatchable, SerializesModels;

    public $student;

    public function __construct(Student $student)
    {
        $this->student = $student;
    }
}
