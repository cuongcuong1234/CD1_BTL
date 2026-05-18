<?php

namespace App\Listeners;

use App\Events\GradeCreated;
use Illuminate\Support\Facades\Log;

class LogGradeCreation
{
    /**
     * Handle the event.
     */
    public function handle(GradeCreated $event): void
    {
        Log::info('Điểm mới được tạo', [
            'grade_id' => $event->grade->id,
            'student_id' => $event->grade->student_id,
            'subject_id' => $event->grade->subject_id,
            'score' => $event->grade->score,
            'grade_letter' => $event->grade->grade_letter,
        ]);
    }
}
