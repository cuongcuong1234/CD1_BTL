<?php

namespace App\Services;

use App\Repositories\TeacherRepository;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class TeacherService
{
    protected $teacherRepository;

    public function __construct(TeacherRepository $teacherRepository)
    {
        $this->teacherRepository = $teacherRepository;
    }

    /**
     * Lấy danh sách giáo viên
     */
    public function getTeachers($search = null, $departmentId = null, $perPage = 15)
    {
        return $this->teacherRepository->getTeachersList($search, $departmentId, $perPage);
    }

    /**
     * Lấy chi tiết giáo viên
     */
    public function getTeacherDetail($id)
    {
        $cacheKey = "teacher_{$id}";
        
        return Cache::remember($cacheKey, 3600, function () use ($id) {
            return $this->teacherRepository->findOrFail($id, ['*'], ['classrooms']);
        });
    }

    /**
     * Tạo giáo viên mới
     */
    public function createTeacher(array $data)
    {
        try {
            DB::beginTransaction();

            $teacher = $this->teacherRepository->create($data);

            Cache::forget('teachers_list');

            DB::commit();

            return $teacher;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Cập nhật giáo viên
     */
    public function updateTeacher($id, array $data)
    {
        try {
            DB::beginTransaction();

            $teacher = $this->teacherRepository->update($id, $data);

            Cache::forget("teacher_{$id}");
            Cache::forget('teachers_list');

            DB::commit();

            return $teacher;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Xóa giáo viên
     */
    public function deleteTeacher($id)
    {
        try {
            DB::beginTransaction();

            $this->teacherRepository->delete($id);

            Cache::forget("teacher_{$id}");
            Cache::forget('teachers_list');

            DB::commit();

            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Khôi phục giáo viên bị xóa
     */
    public function restoreTeacher($id)
    {
        try {
            DB::beginTransaction();

            $teacher = $this->teacherRepository->restore($id);

            Cache::forget("teacher_{$id}");
            Cache::forget('teachers_list');

            DB::commit();

            return $teacher;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Lấy danh sách giáo viên bị xóa
     */
    public function getTrashedTeachers()
    {
        return $this->teacherRepository->getTrashed();
    }
}
