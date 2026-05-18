<?php

namespace App\Listeners;

use App\Events\StudentCreated;
use Illuminate\Support\Facades\Log;

class LogStudentCreation
{
    /**
     * Handle the event.
     */
    public function handle(StudentCreated $event): void
    {
        Log::info('Sinh viên mới được tạo: ' . $event->student->name, [
            'student_id' => $event->student->id,
            'student_code' => $event->student->student_code,
        ]);
    }
}
