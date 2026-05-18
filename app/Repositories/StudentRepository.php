<?php

namespace App\Repositories;

use App\Models\Student;

class StudentRepository extends BaseRepository
{
    public function __construct(Student $model)
    {
        parent::__construct($model);
    }

    /**
     * Lấy danh sách sinh viên với filter và search
     */
    public function getStudentsList(
        $search = null,
        $status = null,
        $classroomId = null,
        $sort = 'created_at',
        $order = 'desc',
        $perPage = 10
    ) {
        $query = $this->model->newQuery();

        // Eager load relationships
        $query->with(['classroom', 'grades.subject', 'enrollments']);

        // Search
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('student_code', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($status) {
            $query->where('status', $status);
        }

        // Filter by classroom
        if ($classroomId) {
            $query->where('classroom_id', $classroomId);
        }

        return $query->orderBy($sort, $order)->paginate($perPage);
    }

    /**
     * Lấy sinh viên theo lớp
     */
    public function getByClassroom($classroomId)
    {
        return $this->model->where('classroom_id', $classroomId)
            ->with(['grades', 'enrollments.subject'])
            ->get();
    }

    /**
     * Lấy sinh viên theo môn học
     */
    public function getBySubject($subjectId)
    {
        return $this->model->whereHas('enrollments', function ($query) use ($subjectId) {
            $query->where('subject_id', $subjectId);
        })->with(['classroom', 'enrollments'])
            ->get();
    }

    /**
     * Lấy danh sách sinh viên có điểm
     */
    public function getStudentsWithGrades()
    {
        return $this->model->with(['grades.subject', 'classroom'])
            ->whereHas('grades')
            ->get();
    }

    /**
     * Tìm sinh viên bằng student code
     */
    public function findByCode($code)
    {
        return $this->model->where('student_code', $code)
            ->with(['classroom', 'grades'])
            ->first();
    }

    /**
     * Lấy sinh viên bị xóa mềm
     */
    public function getTrashed()
    {
        return $this->model->onlyTrashed()
            ->with('classroom')
            ->get();
    }

    /**
     * Khôi phục sinh viên bị xóa mềm
     */
    public function restore($id)
    {
        $student = $this->model->onlyTrashed()->find($id);
        if ($student) {
            $student->restore();
        }
        return $student;
    }
}
