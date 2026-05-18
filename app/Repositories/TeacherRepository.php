<?php

namespace App\Repositories;

use App\Models\Teacher;

class TeacherRepository extends BaseRepository
{
    public function __construct(Teacher $model)
    {
        parent::__construct($model);
    }

    /**
     * Lấy danh sách giáo viên với filter
     */
    public function getTeachersList($search = null, $departmentId = null, $perPage = 15)
    {
        $query = $this->model->newQuery();

        // Eager load relationships
        $query->with(['classrooms']);

        if ($search) {
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
        }

        if ($departmentId) {
            $query->where('department_id', $departmentId);
        }

        return $query->orderBy('created_at', 'desc')->paginate($perPage);
    }

    /**
     * Lấy giáo viên theo môn học (thông qua lớp học)
     */
    public function getBySubject($subjectId)
    {
        return $this->model->with('classrooms')->get();
    }

    /**
     * Tìm giáo viên bằng email
     */
    public function findByEmail($email)
    {
        return $this->model->where('email', $email)->first();
    }

    /**
     * Lấy giáo viên bị xóa mềm
     */
    public function getTrashed()
    {
        return $this->model->onlyTrashed()
            ->with(['classrooms'])
            ->get();
    }

    /**
     * Khôi phục giáo viên bị xóa mềm
     */
    public function restore($id)
    {
        $teacher = $this->model->onlyTrashed()->find($id);
        if ($teacher) {
            $teacher->restore();
        }
        return $teacher;
    }
}
