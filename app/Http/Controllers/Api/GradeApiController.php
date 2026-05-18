<?php

namespace App\Http\Controllers\Api;

use App\Services\GradeService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class GradeApiController
{
    protected $gradeService;

    public function __construct(GradeService $gradeService)
    {
        $this->gradeService = $gradeService;
    }

    /**
     * Lấy danh sách điểm
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $grades = $this->gradeService->getGrades(
                $request->student_id,
                $request->subject_id,
                $request->per_page ?? 20
            );

            return response()->json([
                'success' => true,
                'data' => $grades,
                'message' => 'Danh sách điểm'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Lấy chi tiết điểm
     */
    public function show($id): JsonResponse
    {
        try {
            $grade = $this->gradeService->getGradeDetail($id);

            return response()->json([
                'success' => true,
                'data' => $grade,
                'message' => 'Chi tiết điểm'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Điểm không tồn tại'
            ], 404);
        }
    }

    /**
     * Tạo hoặc cập nhật điểm
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'student_id' => 'required|exists:students,id',
                'subject_id' => 'required|exists:subjects,id',
                'score' => 'required|numeric|min:0|max:10',
                'teacher_id' => 'sometimes|exists:teachers,id',
            ]);

            $grade = $this->gradeService->createOrUpdateGrade($validated);

            return response()->json([
                'success' => true,
                'data' => $grade,
                'message' => 'Tạo/cập nhật điểm thành công'
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Cập nhật điểm
     */
    public function update(Request $request, $id): JsonResponse
    {
        try {
            $validated = $request->validate([
                'score' => 'required|numeric|min:0|max:10',
                'teacher_id' => 'sometimes|exists:teachers,id',
            ]);

            $grade = $this->gradeService->updateGrade($id, $validated);

            return response()->json([
                'success' => true,
                'data' => $grade,
                'message' => 'Cập nhật điểm thành công'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Xóa điểm
     */
    public function destroy($id): JsonResponse
    {
        try {
            $this->gradeService->deleteGrade($id);

            return response()->json([
                'success' => true,
                'message' => 'Xóa điểm thành công'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Lấy thống kê điểm
     */
    public function statistics(): JsonResponse
    {
        try {
            $stats = $this->gradeService->getGradeStatistics();

            return response()->json([
                'success' => true,
                'data' => $stats,
                'message' => 'Thống kê điểm'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Lấy điểm trung bình sinh viên
     */
    public function averageGrade($studentId): JsonResponse
    {
        try {
            $average = $this->gradeService->getAverageGrade($studentId);

            return response()->json([
                'success' => true,
                'data' => ['average' => $average],
                'message' => 'Điểm trung bình sinh viên'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }
}
