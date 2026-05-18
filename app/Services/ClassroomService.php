<?php

namespace App\Services;

use App\Repositories\ClassroomRepository;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class ClassroomService
{
    protected $classroomRepository;

    public function __construct(ClassroomRepository $classroomRepository)
    {
        $this->classroomRepository = $classroomRepository;
    }

    /**
     * Lấy danh sách lớp
     */
    public function getClassrooms($search = null, $perPage = 15)
    {
        return $this->classroomRepository->getClassroomsList($search, $perPage);
    }

    /**
     * Lấy chi tiết lớp
     */
    public function getClassroomDetail($id)
    {
        $cacheKey = "classroom_{$id}";
        
        return Cache::remember($cacheKey, 3600, function () use ($id) {
            return $this->classroomRepository->findOrFail($id, ['*'], ['students', 'teacher']);
        });
    }

    /**
     * Tạo lớp mới
     */
    public function createClassroom(array $data)
    {
        try {
            DB::beginTransaction();

            $classroom = $this->classroomRepository->create($data);

            Cache::forget('classrooms_list');

            DB::commit();

            return $classroom;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Cập nhật lớp
     */
    public function updateClassroom($id, array $data)
    {
        try {
            DB::beginTransaction();

            $classroom = $this->classroomRepository->update($id, $data);

            Cache::forget("classroom_{$id}");
            Cache::forget('classrooms_list');

            DB::commit();

            return $classroom;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Xóa lớp
     */
    public function deleteClassroom($id)
    {
        try {
            DB::beginTransaction();

            $this->classroomRepository->delete($id);

            Cache::forget("classroom_{$id}");
            Cache::forget('classrooms_list');

            DB::commit();

            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Lấy danh sách lớp với thống kê
     */
    public function getClassroomsWithStats()
    {
        $cacheKey = 'classrooms_stats';
        
        return Cache::remember($cacheKey, 3600, function () {
            return $this->classroomRepository->getWithStats();
        });
    }

    /**
     * Khôi phục lớp bị xóa
     */
    public function restoreClassroom($id)
    {
        try {
            DB::beginTransaction();

            $classroom = $this->classroomRepository->restore($id);

            Cache::forget("classroom_{$id}");
            Cache::forget('classrooms_list');

            DB::commit();

            return $classroom;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Lấy danh sách lớp bị xóa
     */
    public function getTrashedClassrooms()
    {
        return $this->classroomRepository->getTrashed();
    }
}
