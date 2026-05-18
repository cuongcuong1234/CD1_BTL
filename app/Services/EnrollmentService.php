<?php

namespace App\Services;

use App\Repositories\EnrollmentRepository;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class EnrollmentService
{
    protected $enrollmentRepository;

    public function __construct(EnrollmentRepository $enrollmentRepository)
    {
        $this->enrollmentRepository = $enrollmentRepository;
    }

    /**
     * Lấy danh sách ghi danh
     */
    public function getEnrollments($studentId = null, $subjectId = null, $perPage = 20)
    {
        return $this->enrollmentRepository->getEnrollmentsList($studentId, $subjectId, $perPage);
    }

    /**
     * Ghi danh sinh viên vào môn học
     */
    public function enrollStudent($studentId, $subjectId)
    {
        try {
            DB::beginTransaction();

            $enrollment = $this->enrollmentRepository->enroll($studentId, $subjectId);

            if ($enrollment) {
                Cache::forget("student_enrollments_{$studentId}");
                Cache::forget("enrollments_stats");
            }

            DB::commit();

            return $enrollment;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Xóa ghi danh
     */
    public function unenrollStudent($studentId, $subjectId)
    {
        try {
            DB::beginTransaction();

            $result = $this->enrollmentRepository->unenroll($studentId, $subjectId);

            Cache::forget("student_enrollments_{$studentId}");
            Cache::forget("enrollments_stats");

            DB::commit();

            return $result;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Lấy môn học của sinh viên
     */
    public function getStudentEnrollments($studentId)
    {
        $cacheKey = "student_enrollments_{$studentId}";
        
        return Cache::remember($cacheKey, 3600, function () use ($studentId) {
            return $this->enrollmentRepository->getStudentEnrollments($studentId);
        });
    }

    /**
     * Kiểm tra sinh viên đã ghi danh môn này chưa
     */
    public function isEnrolled($studentId, $subjectId)
    {
        return $this->enrollmentRepository->isEnrolled($studentId, $subjectId);
    }

    /**
     * Lấy thống kê ghi danh
     */
    public function getEnrollmentStatistics()
    {
        $cacheKey = 'enrollments_stats';
        
        return Cache::remember($cacheKey, 3600, function () {
            return $this->enrollmentRepository->getEnrollmentStatistics();
        });
    }
}
