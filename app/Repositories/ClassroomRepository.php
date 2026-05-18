<?php

namespace App\Repositories;

use App\Models\Classroom;

class ClassroomRepository extends BaseRepository
{
    public function __construct(Classroom $model)
    {
        parent::__construct($model);
    }

    /**
     * Lấy danh sách lớp với filter
     */
    public function getClassroomsList($search = null, $perPage = 15)
    {
        $query = $this->model->newQuery();

        // Eager load relationships
        $query->with(['students', 'teacher']);

        if ($search) {
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('class_code', 'like', "%{$search}%");
        }

        return $query->orderBy('created_at', 'desc')->paginate($perPage);
    }

    /**
     * Lấy lớp với thống kê sinh viên
     */
    public function getWithStats()
    {
        return $this->model->with('students')
            ->withCount('students')
            ->get();
    }

    /**
     * Lấy lớp theo mã lớp
     */
    public function findByCode($code)
    {
        return $this->model->where('class_code', $code)
            ->with(['students', 'teacher'])
            ->first();
    }

    /**
     * Lấy danh sách lớp có học sinh
     */
    public function getClassroomsWithStudents()
    {
        return $this->model->with('students')
            ->whereHas('students')
            ->get();
    }

    /**
     * Lấy lớp bị xóa mềm
     */
    public function getTrashed()
    {
        return $this->model->onlyTrashed()
            ->with('students')
            ->get();
    }

    /**
     * Khôi phục lớp bị xóa mềm
     */
    public function restore($id)
    {
        $classroom = $this->model->onlyTrashed()->find($id);
        if ($classroom) {
            $classroom->restore();
        }
        return $classroom;
    }
}
