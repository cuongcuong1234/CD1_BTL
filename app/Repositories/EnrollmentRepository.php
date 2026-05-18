<?php

namespace App\Repositories;

use App\Models\Enrollment;

class EnrollmentRepository extends BaseRepository
{
    public function __construct(Enrollment $model)
    {
        parent::__construct($model);
    }

    /**
     * Lấy danh sách ghi danh với filter
     */
    public function getEnrollmentsList($studentId = null, $subjectId = null, $perPage = 20)
    {
        $query = $this->model->newQuery();

        // Eager load relationships
        $query->with(['student', 'subject']);

        if ($studentId) {
            $query->where('student_id', $studentId);
        }

        if ($subjectId) {
            $query->where('subject_id', $subjectId);
        }

        return $query->orderBy('created_at', 'desc')->paginate($perPage);
    }

    /**
     * Lấy môn học của sinh viên
     */
    public function getStudentEnrollments($studentId)
    {
        return $this->model->where('student_id', $studentId)
            ->with('subject')
            ->get();
    }

    /**
     * Kiểm tra sinh viên đã ghi danh môn này chưa
     */
    public function isEnrolled($studentId, $subjectId)
    {
        return $this->model->where('student_id', $studentId)
            ->where('subject_id', $subjectId)
            ->exists();
    }

    /**
     * Ghi danh sinh viên vào môn học
     */
    public function enroll($studentId, $subjectId)
    {
        if (!$this->isEnrolled($studentId, $subjectId)) {
            return $this->create([
                'student_id' => $studentId,
                'subject_id' => $subjectId,
            ]);
        }
        return false;
    }

    /**
     * Xóa ghi danh
     */
    public function unenroll($studentId, $subjectId)
    {
        return $this->model->where('student_id', $studentId)
            ->where('subject_id', $subjectId)
            ->delete();
    }

    /**
     * Lấy thống kê ghi danh
     */
    public function getEnrollmentStatistics()
    {
        return [
            'total_enrollments' => $this->count(),
            'total_students_enrolled' => $this->model->distinct('student_id')->count('student_id'),
            'total_subjects_enrolled' => $this->model->distinct('subject_id')->count('subject_id'),
        ];
    }
}
