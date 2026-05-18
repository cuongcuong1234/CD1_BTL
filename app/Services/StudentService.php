<?php

namespace App\Services;

use App\Repositories\StudentRepository;
use App\Events\StudentCreated;
use App\Events\StudentUpdated;
use App\Events\StudentDeleted;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class StudentService
{
    protected $studentRepository;

    public function __construct(StudentRepository $studentRepository)
    {
        $this->studentRepository = $studentRepository;
    }

    /**
     * Lấy danh sách sinh viên
     */
    public function getStudents($search = null, $status = null, $classroomId = null, $sort = 'created_at', $order = 'desc', $perPage = 10)
    {
        return $this->studentRepository->getStudentsList($search, $status, $classroomId, $sort, $order, $perPage);
    }

    /**
     * Lấy chi tiết sinh viên
     */
    public function getStudentDetail($id)
    {
        $cacheKey = "student_{$id}";
        
        return Cache::remember($cacheKey, 3600, function () use ($id) {
            return $this->studentRepository->findOrFail($id, ['*'], ['classroom', 'grades.subject', 'enrollments.subject']);
        });
    }

    /**
     * Tạo sinh viên mới
     */
    public function createStudent(array $data)
    {
        try {
            DB::beginTransaction();

            $student = $this->studentRepository->create($data);

            // Trigger event
            event(new StudentCreated($student));

            // Clear cache
            Cache::forget('students_list');

            DB::commit();

            return $student;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Cập nhật sinh viên
     */
    public function updateStudent($id, array $data)
    {
        try {
            DB::beginTransaction();

            $student = $this->studentRepository->update($id, $data);

            // Trigger event
            event(new StudentUpdated($student));

            // Clear cache
            Cache::forget("student_{$id}");
            Cache::forget('students_list');

            DB::commit();

            return $student;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Xóa sinh viên
     */
    public function deleteStudent($id)
    {
        try {
            DB::beginTransaction();

            $student = $this->studentRepository->find($id);
            $this->studentRepository->delete($id);

            // Trigger event
            event(new StudentDeleted($student));

            // Clear cache
            Cache::forget("student_{$id}");
            Cache::forget('students_list');

            DB::commit();

            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Lấy sinh viên theo lớp
     */
    public function getStudentsByClassroom($classroomId)
    {
        $cacheKey = "classroom_students_{$classroomId}";
        
        return Cache::remember($cacheKey, 3600, function () use ($classroomId) {
            return $this->studentRepository->getByClassroom($classroomId);
        });
    }

    /**
     * Lấy sinh viên theo môn học
     */
    public function getStudentsBySubject($subjectId)
    {
        $cacheKey = "subject_students_{$subjectId}";
        
        return Cache::remember($cacheKey, 3600, function () use ($subjectId) {
            return $this->studentRepository->getBySubject($subjectId);
        });
    }

    /**
     * Khôi phục sinh viên bị xóa
     */
    public function restoreStudent($id)
    {
        try {
            DB::beginTransaction();

            $student = $this->studentRepository->restore($id);

            // Clear cache
            Cache::forget("student_{$id}");
            Cache::forget('students_list');

            DB::commit();

            return $student;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Lấy danh sách sinh viên bị xóa
     */
    public function getTrashedStudents()
    {
        return $this->studentRepository->getTrashed();
    }

    /**
     * Lấy sinh viên bằng student code
     */
    public function findByCode($code)
    {
        return $this->studentRepository->findByCode($code);
    }
}
