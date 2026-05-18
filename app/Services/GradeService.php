<?php

namespace App\Services;

use App\Repositories\GradeRepository;
use App\Events\GradeCreated;
use App\Events\GradeUpdated;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class GradeService
{
    protected $gradeRepository;

    public function __construct(GradeRepository $gradeRepository)
    {
        $this->gradeRepository = $gradeRepository;
    }

    /**
     * Lấy danh sách điểm
     */
    public function getGrades($studentId = null, $subjectId = null, $perPage = 20)
    {
        return $this->gradeRepository->getGradesList($studentId, $subjectId, $perPage);
    }

    /**
     * Lấy chi tiết điểm
     */
    public function getGradeDetail($id)
    {
        return $this->gradeRepository->findOrFail($id, ['*'], ['student.classroom', 'subject']);
    }

    /**
     * Tạo hoặc cập nhật điểm
     */
    public function createOrUpdateGrade(array $data)
    {
        try {
            DB::beginTransaction();

            $studentId = $data['student_id'];
            $subjectId = $data['subject_id'];

            // Validate score
            if (!isset($data['score']) || $data['score'] < 0 || $data['score'] > 10) {
                throw new \Exception('Điểm phải từ 0 đến 10');
            }

            // Calculate grade letter
            $gradeLetter = $this->calculateGradeLetter($data['score']);
            $data['grade_letter'] = $gradeLetter;

            $grade = $this->gradeRepository->updateOrCreateGrade($studentId, $subjectId, $data);

            // Trigger event
            event(new GradeCreated($grade));

            // Clear cache
            Cache::forget("student_grades_{$studentId}");
            Cache::forget("subject_grades_{$subjectId}");
            Cache::forget("average_grade_{$studentId}");

            DB::commit();

            return $grade;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Cập nhật điểm
     */
    public function updateGrade($id, array $data)
    {
        try {
            DB::beginTransaction();

            $grade = $this->gradeRepository->findOrFail($id);

            // Validate score
            if (isset($data['score']) && ($data['score'] < 0 || $data['score'] > 10)) {
                throw new \Exception('Điểm phải từ 0 đến 10');
            }

            // Calculate grade letter
            if (isset($data['score'])) {
                $data['grade_letter'] = $this->calculateGradeLetter($data['score']);
            }

            $grade->update($data);

            // Trigger event
            event(new GradeUpdated($grade));

            // Clear cache
            Cache::forget("student_grades_{$grade->student_id}");
            Cache::forget("subject_grades_{$grade->subject_id}");
            Cache::forget("average_grade_{$grade->student_id}");

            DB::commit();

            return $grade;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Xóa điểm
     */
    public function deleteGrade($id)
    {
        try {
            DB::beginTransaction();

            $grade = $this->gradeRepository->find($id);
            $this->gradeRepository->delete($id);

            // Clear cache
            Cache::forget("student_grades_{$grade->student_id}");
            Cache::forget("subject_grades_{$grade->subject_id}");
            Cache::forget("average_grade_{$grade->student_id}");

            DB::commit();

            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Lấy điểm của sinh viên
     */
    public function getStudentGrades($studentId)
    {
        $cacheKey = "student_grades_{$studentId}";
        
        return Cache::remember($cacheKey, 3600, function () use ($studentId) {
            return $this->gradeRepository->getStudentGrades($studentId);
        });
    }

    /**
     * Lấy điểm trung bình sinh viên
     */
    public function getAverageGrade($studentId)
    {
        $cacheKey = "average_grade_{$studentId}";
        
        return Cache::remember($cacheKey, 3600, function () use ($studentId) {
            return $this->gradeRepository->getAverageGrade($studentId);
        });
    }

    /**
     * Lấy các điểm cao nhất
     */
    public function getTopGrades($limit = 10)
    {
        $cacheKey = "top_grades_{$limit}";
        
        return Cache::remember($cacheKey, 3600, function () use ($limit) {
            return $this->gradeRepository->getTopGrades($limit);
        });
    }

    /**
     * Lấy thống kê điểm
     */
    public function getGradeStatistics()
    {
        $cacheKey = 'grade_statistics';
        
        return Cache::remember($cacheKey, 3600, function () {
            return $this->gradeRepository->getGradeStatistics();
        });
    }

    /**
     * Tính điểm chữ
     */
    private function calculateGradeLetter($score)
    {
        if ($score >= 8.5) return 'A';
        if ($score >= 7) return 'B';
        if ($score >= 5.5) return 'C';
        if ($score >= 4) return 'D';
        return 'F';
    }
}
