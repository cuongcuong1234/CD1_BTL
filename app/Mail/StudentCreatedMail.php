<?php

namespace App\Mail;

use App\Models\Student;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class StudentCreatedMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $student;

    public function __construct(Student $student)
    {
        $this->student = $student;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Chào mừng bạn đến với hệ thống quản lý học tập',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.student-created',
            with: [
                'student' => $this->student,
            ],
        );
    }
}
