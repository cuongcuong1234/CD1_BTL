<?php

namespace App\Http\Controllers;

use App\Services\EnrollmentService;
use App\Services\StudentService;
use App\Services\SubjectService;
use Illuminate\Http\Request;

class EnrollmentController extends Controller
{
    protected $enrollmentService;
    protected $studentService;
    protected $subjectService;

    public function __construct(
        EnrollmentService $enrollmentService,
        StudentService $studentService,
        SubjectService $subjectService
    ) {
        $this->enrollmentService = $enrollmentService;
        $this->studentService = $studentService;
        $this->subjectService = $subjectService;
    }

    /**
     * Hiển thị danh sách ghi danh
     */
    public function index(Request $request)
    {
        try {
            $studentId = $request->student_id ?? null;
            $subjectId = $request->subject_id ?? null;

            $enrollments = $this->enrollmentService->getEnrollments($studentId, $subjectId);
            $students = $this->studentService->getStudents();
            $subjects = $this->subjectService->getSubjects();

            return view('enrollments.index', compact('enrollments', 'students', 'subjects'));
        } catch (\Exception $e) {
            \Log::error('Error fetching enrollments: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Lỗi khi tải danh sách ghi danh');
        }
    }

    /**
     * Form ghi danh
     */
    public function create()
    {
        try {
            $students = $this->studentService->getStudents();
            $subjects = $this->subjectService->getSubjects();
            return view('enrollments.create', compact('students', 'subjects'));
        } catch (\Exception $e) {
            \Log::error('Error loading create form: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Lỗi khi tải form');
        }
    }

    /**
     * Xem chi tiết ghi danh
     */
    public function show($id)
    {
        try {
            $enrollment = $this->enrollmentService->getEnrollments(null, null);
            return view('enrollments.show', compact('enrollment'));
        } catch (\Exception $e) {
            \Log::error('Error fetching enrollment: ' . $e->getMessage());
            return redirect()->route('enrollments.index')
                            ->with('error', 'Ghi danh không tồn tại');
        }
    }

    /**
     * Lưu ghi danh mới
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'student_id' => 'required|exists:students,id',
                'subject_id' => 'required|exists:subjects,id',
            ]);

            $studentId = $request->student_id;
            $subjectId = $request->subject_id;

            // Kiểm tra đã ghi danh chưa
            if ($this->enrollmentService->isEnrolled($studentId, $subjectId)) {
                return redirect()->back()
                               ->with('error', 'Sinh viên đã ghi danh môn học này')
                               ->withInput();
            }

            $this->enrollmentService->enrollStudent($studentId, $subjectId);

            return redirect()->route('enrollments.index')
                            ->with('success', 'Ghi danh môn học thành công');
        } catch (\Exception $e) {
            \Log::error('Error creating enrollment: ' . $e->getMessage());
            return redirect()->back()
                            ->with('error', 'Lỗi khi ghi danh: ' . $e->getMessage())
                            ->withInput();
        }
    }

    /**
     * Form sửa ghi danh
     */
    public function edit($id)
    {
        try {
            $students = $this->studentService->getStudents();
            $subjects = $this->subjectService->getSubjects();
            return view('enrollments.edit', compact('students', 'subjects'));
        } catch (\Exception $e) {
            \Log::error('Error loading edit form: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Lỗi khi tải form');
        }
    }

    /**
     * Cập nhật ghi danh
     */
    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'status' => 'required|in:enrolled,completed,dropped',
            ]);

            // Cập nhật ghi danh
            // Cần thêm method trong service

            return redirect()->route('enrollments.index')
                            ->with('success', 'Cập nhật ghi danh thành công');
        } catch (\Exception $e) {
            \Log::error('Error updating enrollment: ' . $e->getMessage());
            return redirect()->back()
                            ->with('error', 'Lỗi khi cập nhật ghi danh: ' . $e->getMessage())
                            ->withInput();
        }
    }

    /**
     * Xóa ghi danh
     */
    public function destroy($id)
    {
        try {
            // Cần lấy thông tin ghi danh trước
            // Sửa sau

            return redirect()->route('enrollments.index')
                            ->with('success', 'Xóa ghi danh thành công');
        } catch (\Exception $e) {
            \Log::error('Error deleting enrollment: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Lỗi khi xóa ghi danh');
        }
    }
}
