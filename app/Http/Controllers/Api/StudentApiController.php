<?php

namespace App\Http\Controllers\Api;

use App\Services\StudentService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class StudentApiController
{
    protected $studentService;

    public function __construct(StudentService $studentService)
    {
        $this->studentService = $studentService;
    }

    /**
     * Lấy danh sách sinh viên
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $students = $this->studentService->getStudents(
                $request->search,
                $request->status,
                $request->classroom_id,
                $request->sort ?? 'created_at',
                $request->order ?? 'desc',
                $request->per_page ?? 15
            );

            return response()->json([
                'success' => true,
                'data' => $students,
                'message' => 'Danh sách sinh viên'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Lấy chi tiết sinh viên
     */
    public function show($id): JsonResponse
    {
        try {
            $student = $this->studentService->getStudentDetail($id);

            return response()->json([
                'success' => true,
                'data' => $student,
                'message' => 'Chi tiết sinh viên'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Sinh viên không tồn tại'
            ], 404);
        }
    }

    /**
     * Tạo sinh viên mới
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'student_code' => 'required|unique:students,student_code',
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:students,email',
                'phone' => 'required|string',
                'address' => 'required|string',
                'classroom_id' => 'required|exists:classrooms,id',
                'status' => 'required|in:active,inactive',
            ]);

            $student = $this->studentService->createStudent($validated);

            return response()->json([
                'success' => true,
                'data' => $student,
                'message' => 'Tạo sinh viên thành công'
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Cập nhật sinh viên
     */
    public function update(Request $request, $id): JsonResponse
    {
        try {
            $validated = $request->validate([
                'name' => 'sometimes|string|max:255',
                'email' => 'sometimes|email|unique:students,email,' . $id,
                'phone' => 'sometimes|string',
                'address' => 'sometimes|string',
                'classroom_id' => 'sometimes|exists:classrooms,id',
                'status' => 'sometimes|in:active,inactive',
            ]);

            $student = $this->studentService->updateStudent($id, $validated);

            return response()->json([
                'success' => true,
                'data' => $student,
                'message' => 'Cập nhật sinh viên thành công'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Xóa sinh viên
     */
    public function destroy($id): JsonResponse
    {
        try {
            $this->studentService->deleteStudent($id);

            return response()->json([
                'success' => true,
                'message' => 'Xóa sinh viên thành công'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }
}
