<?php

namespace App\Repositories;

use App\Models\Subject;

class SubjectRepository extends BaseRepository
{
    public function __construct(Subject $model)
    {
        parent::__construct($model);
    }

    /**
     * Lấy danh sách môn học với filter
     */
    public function getSubjectsList($search = null, $perPage = 15)
    {
        $query = $this->model->newQuery();

        // Eager load relationships
        $query->with(['grades']);

        if ($search) {
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%");
        }

        return $query->orderBy('created_at', 'desc')->paginate($perPage);
    }

    /**
     * Lấy môn học theo mã
     */
    public function findByCode($code)
    {
        return $this->model->where('code', $code)
            ->with(['grades'])
            ->first();
    }

    /**
     * Lấy danh sách môn học có giáo viên
     */
    public function getSubjectsWithTeachers()
    {
        return $this->model->with('enrollments')
            ->whereHas('enrollments')
            ->get();
    }

    /**
     * Lấy môn học bị xóa mềm
     */
    public function getTrashed()
    {
        return $this->model->onlyTrashed()
            ->with(['grades'])
            ->get();
    }

    /**
     * Khôi phục môn học bị xóa mềm
     */
    public function restore($id)
    {
        $subject = $this->model->onlyTrashed()->find($id);
        if ($subject) {
            $subject->restore();
        }
        return $subject;
    }
}
