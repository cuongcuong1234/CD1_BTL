<?php

namespace App\Events;

use App\Models\Grade;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class GradeUpdated
{
    use Dispatchable, SerializesModels;

    public $grade;

    public function __construct(Grade $grade)
    {
        $this->grade = $grade;
    }
}
