<?php

namespace App\Listeners;

use App\Events\GradeUpdated;
use Illuminate\Support\Facades\Log;

class LogGradeUpdate
{
    /**
     * Handle the event.
     */
    public function handle(GradeUpdated $event): void
    {
        Log::info('Điểm được cập nhật', [
            'grade_id' => $event->grade->id,
            'student_id' => $event->grade->student_id,
            'subject_id' => $event->grade->subject_id,
            'score' => $event->grade->score,
            'grade_letter' => $event->grade->grade_letter,
        ]);
    }
}
