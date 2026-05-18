<?php

namespace App\Listeners;

use App\Events\StudentDeleted;
use Illuminate\Support\Facades\Log;

class LogStudentDeletion
{
    /**
     * Handle the event.
     */
    public function handle(StudentDeleted $event): void
    {
        Log::info('Sinh viên được xóa: ' . $event->student->name, [
            'student_id' => $event->student->id,
            'student_code' => $event->student->student_code,
        ]);
    }
}
