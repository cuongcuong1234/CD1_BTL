<?php

namespace App\Jobs;

use App\Models\Grade;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class GenerateGradeReport implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $studentId;

    public function __construct($studentId = null)
    {
        $this->studentId = $studentId;
    }

    public function handle(): void
    {
        $query = Grade::with('student', 'subject');

        if ($this->studentId) {
            $query->where('student_id', $this->studentId);
        }

        $grades = $query->get();

        // Tạo file report
        $reportPath = storage_path('reports/grade_report_' . Carbon::now()->format('Y-m-d_H-i-s') . '.csv');

        $handle = fopen($reportPath, 'w');
        fputcsv($handle, ['Sinh viên', 'Môn học', 'Điểm', 'Điểm chữ']);

        foreach ($grades as $grade) {
            fputcsv($handle, [
                $grade->student->name,
                $grade->subject->name,
                $grade->score,
                $grade->grade_letter,
            ]);
        }

        fclose($handle);

        Log::info('Grade report generated', ['path' => $reportPath]);
    }
}
