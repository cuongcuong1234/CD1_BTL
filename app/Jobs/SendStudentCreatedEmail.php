<?php

namespace App\Jobs;

use App\Mail\StudentCreatedMail;
use App\Models\Student;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendStudentCreatedEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $student;

    public function __construct(Student $student)
    {
        $this->student = $student;
    }

    public function handle(): void
    {
        // Gửi email khi sinh viên được tạo
        Mail::to($this->student->email)->queue(new StudentCreatedMail($this->student));
    }
}
