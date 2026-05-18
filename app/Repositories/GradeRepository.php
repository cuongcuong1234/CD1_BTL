<?php

namespace App\Repositories;

use App\Models\Grade;

class GradeRepository extends BaseRepository
{
    public function __construct(Grade $model)
    {
        parent::__construct($model);
    }

    /**
     * Lấy danh sách điểm với filter
     */
    public function getGradesList($studentId = null, $subjectId = null, $perPage = 20)
    {
        $query = $this->model->newQuery();

        // Eager load relationships
        $query->with(['student.classroom', 'subject']);

        if ($studentId) {
            $query->where('student_id', $studentId);
        }

        if ($subjectId) {
            $query->where('subject_id', $subjectId);
        }

        return $query->orderBy('created_at', 'desc')->paginate($perPage);
    }

    /**
     * Lấy điểm của sinh viên
     */
    public function getStudentGrades($studentId)
    {
        return $this->model->where('student_id', $studentId)
            ->with(['subject'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Lấy điểm theo môn học
     */
    public function getSubjectGrades($subjectId)
    {
        return $this->model->where('subject_id', $subjectId)
            ->with(['student'])
            ->orderBy('score', 'desc')
            ->get();
    }

    /**
     * Lấy điểm trung bình sinh viên
     */
    public function getAverageGrade($studentId)
    {
        return $this->model->where('student_id', $studentId)
            ->average('score') ?? 0;
    }

    /**
     * Lấy tất cả điểm cao nhất
     */
    public function getTopGrades($limit = 10)
    {
        return $this->model->with(['student', 'subject'])
            ->orderBy('score', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Tìm hoặc tạo điểm
     */
    public function findOrCreateGrade($studentId, $subjectId)
    {
        return $this->model->firstOrCreate(
            ['student_id' => $studentId, 'subject_id' => $subjectId],
            ['score' => 0]
        );
    }

    /**
     * Cập nhật hoặc tạo điểm
     */
    public function updateOrCreateGrade($studentId, $subjectId, $data)
    {
        return $this->model->updateOrCreate(
            ['student_id' => $studentId, 'subject_id' => $subjectId],
            $data
        );
    }

    /**
     * Lấy thống kê điểm
     */
    public function getGradeStatistics()
    {
        return [
            'total' => $this->count(),
            'average' => $this->model->average('score'),
            'max' => $this->model->max('score'),
            'min' => $this->model->min('score'),
        ];
    }
}
