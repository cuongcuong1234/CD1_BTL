<?php

namespace App\Http\Controllers;

use App\Services\TeacherService;
use App\Http\Requests\StoreTeacherRequest;
use Illuminate\Http\Request;

class TeacherController extends Controller
{
    protected $teacherService;

    public function __construct(TeacherService $teacherService)
    {
        $this->teacherService = $teacherService;
    }

    /**
     * Hiển thị danh sách giáo viên
     */
    public function index(Request $request)
    {
        try {
            $search = $request->search ?? null;
            $teachers = $this->teacherService->getTeachers($search);
            return view('teachers.index', compact('teachers'));
        } catch (\Exception $e) {
            \Log::error('Error fetching teachers: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Lỗi khi tải danh sách giáo viên');
        }
    }

    /**
     * Form thêm giáo viên
     */
    public function create()
    {
        return view('teachers.create');
    }

    /**
     * Lưu giáo viên mới
     */
    public function store(StoreTeacherRequest $request)
    {
        try {
            $teacher = $this->teacherService->createTeacher($request->validated());
            return redirect()->route('teachers.show', $teacher)
                            ->with('success', 'Thêm giáo viên thành công');
        } catch (\Exception $e) {
            \Log::error('Error creating teacher: ' . $e->getMessage());
            return redirect()->back()
                            ->with('error', 'Lỗi khi thêm giáo viên: ' . $e->getMessage())
                            ->withInput();
        }
    }

    /**
     * Xem chi tiết giáo viên
     */
    public function show($id)
    {
        try {
            $teacher = $this->teacherService->getTeacherDetail($id);
            return view('teachers.show', compact('teacher'));
        } catch (\Exception $e) {
            \Log::error('Error fetching teacher: ' . $e->getMessage());
            return redirect()->route('teachers.index')
                            ->with('error', 'Giáo viên không tồn tại');
        }
    }

    /**
     * Form sửa giáo viên
     */
    public function edit($id)
    {
        try {
            $teacher = $this->teacherService->getTeacherDetail($id);
            return view('teachers.edit', compact('teacher'));
        } catch (\Exception $e) {
            \Log::error('Error loading edit form: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Lỗi khi tải form');
        }
    }

    /**
     * Cập nhật giáo viên
     */
    public function update(StoreTeacherRequest $request, $id)
    {
        try {
            $teacher = $this->teacherService->updateTeacher($id, $request->validated());
            return redirect()->route('teachers.show', $teacher)
                            ->with('success', 'Cập nhật giáo viên thành công');
        } catch (\Exception $e) {
            \Log::error('Error updating teacher: ' . $e->getMessage());
            return redirect()->back()
                            ->with('error', 'Lỗi khi cập nhật giáo viên: ' . $e->getMessage())
                            ->withInput();
        }
    }

    /**
     * Xóa giáo viên
     */
    public function destroy($id)
    {
        try {
            $this->teacherService->deleteTeacher($id);
            return redirect()->route('teachers.index')
                            ->with('success', 'Xóa giáo viên thành công');
        } catch (\Exception $e) {
            \Log::error('Error deleting teacher: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Lỗi khi xóa giáo viên');
        }
    }

    /**
     * Khôi phục giáo viên bị xóa
     */
    public function restore($id)
    {
        try {
            $this->teacherService->restoreTeacher($id);
            return redirect()->route('teachers.index')
                            ->with('success', 'Khôi phục giáo viên thành công');
        } catch (\Exception $e) {
            \Log::error('Error restoring teacher: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Lỗi khi khôi phục giáo viên');
        }
    }
}
