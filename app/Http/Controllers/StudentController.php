<?php

namespace App\Http\Controllers;

use App\Services\StudentService;
use App\Services\ClassroomService;
use App\Http\Requests\StoreStudentRequest;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    protected $studentService;
    protected $classroomService;

    public function __construct(StudentService $studentService, ClassroomService $classroomService)
    {
        $this->studentService = $studentService;
        $this->classroomService = $classroomService;
    }

    /**
     * Hiển thị danh sách sinh viên
     */
    public function index(Request $request)
    {
        try {
            $sort = $request->sort ?? 'created_at';
            $order = $request->order ?? 'desc';
            $search = $request->search ?? null;
            $status = $request->status ?? null;
            $classroomId = $request->classroom_id ?? null;

            $students = $this->studentService->getStudents($search, $status, $classroomId, $sort, $order);
            $classrooms = $this->classroomService->getClassrooms();

            return view('students.index', compact('students', 'classrooms'));
        } catch (\Exception $e) {
            \Log::error('Error fetching students: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Lỗi khi tải danh sách sinh viên');
        }
    }

    /**
     * Form thêm sinh viên
     */
    public function create()
    {
        try {
            $classrooms = $this->classroomService->getClassrooms();
            return view('students.create', compact('classrooms'));
        } catch (\Exception $e) {
            \Log::error('Error loading create form: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Lỗi khi tải form');
        }
    }

    /**
     * Lưu sinh viên mới
     */
    public function store(StoreStudentRequest $request)
    {
        try {
            $student = $this->studentService->createStudent($request->validated());
            return redirect()->route('students.show', $student)
                            ->with('success', 'Thêm sinh viên thành công');
        } catch (\Exception $e) {
            \Log::error('Error creating student: ' . $e->getMessage());
            return redirect()->back()
                            ->with('error', 'Lỗi khi thêm sinh viên: ' . $e->getMessage())
                            ->withInput();
        }
    }

    /**
     * Xem chi tiết sinh viên
     */
    public function show($id)
    {
        try {
            $student = $this->studentService->getStudentDetail($id);
            return view('students.show', compact('student'));
        } catch (\Exception $e) {
            \Log::error('Error fetching student: ' . $e->getMessage());
            return redirect()->route('students.index')
                            ->with('error', 'Sinh viên không tồn tại');
        }
    }

    /**
     * Form sửa sinh viên
     */
    public function edit($id)
    {
        try {
            $student = $this->studentService->getStudentDetail($id);
            $classrooms = $this->classroomService->getClassrooms();
            return view('students.edit', compact('student', 'classrooms'));
        } catch (\Exception $e) {
            \Log::error('Error loading edit form: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Lỗi khi tải form');
        }
    }

    /**
     * Cập nhật sinh viên
     */
    public function update(StoreStudentRequest $request, $id)
    {
        try {
            $student = $this->studentService->updateStudent($id, $request->validated());
            return redirect()->route('students.show', $student)
                            ->with('success', 'Cập nhật sinh viên thành công');
        } catch (\Exception $e) {
            \Log::error('Error updating student: ' . $e->getMessage());
            return redirect()->back()
                            ->with('error', 'Lỗi khi cập nhật sinh viên: ' . $e->getMessage())
                            ->withInput();
        }
    }

    /**
     * Xóa sinh viên (soft delete)
     */
    public function destroy($id)
    {
        try {
            $this->studentService->deleteStudent($id);
            return redirect()->route('students.index')
                            ->with('success', 'Xóa sinh viên thành công');
        } catch (\Exception $e) {
            \Log::error('Error deleting student: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Lỗi khi xóa sinh viên');
        }
    }

    /**
     * Khôi phục sinh viên đã xóa
     */
    public function restore($id)
    {
        try {
            $this->studentService->restoreStudent($id);
            return redirect()->route('students.index')
                            ->with('success', 'Khôi phục sinh viên thành công');
        } catch (\Exception $e) {
            \Log::error('Error restoring student: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Lỗi khi khôi phục sinh viên');
        }
    }

    /**
     * Danh sách sinh viên đã xóa
     */
    public function trashed()
    {
        try {
            $students = $this->studentService->getTrashedStudents();
            return view('students.trashed', compact('students'));
        } catch (\Exception $e) {
            \Log::error('Error fetching trashed students: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Lỗi khi tải danh sách sinh viên đã xóa');
        }
    }
}
