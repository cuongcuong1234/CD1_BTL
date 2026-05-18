<?php

namespace App\Listeners;

use App\Events\StudentUpdated;
use Illuminate\Support\Facades\Log;

class LogStudentUpdate
{
    /**
     * Handle the event.
     */
    public function handle(StudentUpdated $event): void
    {
        Log::info('Sinh viên được cập nhật: ' . $event->student->name, [
            'student_id' => $event->student->id,
            'student_code' => $event->student->student_code,
        ]);
    }
}
