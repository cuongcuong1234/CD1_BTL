<?php

namespace App\Http\Controllers;

use App\Services\ClassroomService;
use App\Services\TeacherService;
use App\Http\Requests\StoreClassroomRequest;
use Illuminate\Http\Request;

class ClassroomController extends Controller
{
    protected $classroomService;
    protected $teacherService;

    public function __construct(ClassroomService $classroomService, TeacherService $teacherService)
    {
        $this->classroomService = $classroomService;
        $this->teacherService = $teacherService;
    }

    /**
     * Hiển thị danh sách lớp học
     */
    public function index(Request $request)
    {
        try {
            $search = $request->search ?? null;
            $classrooms = $this->classroomService->getClassrooms($search);
            return view('classrooms.index', compact('classrooms'));
        } catch (\Exception $e) {
            \Log::error('Error fetching classrooms: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Lỗi khi tải danh sách lớp học');
        }
    }

    /**
     * Form thêm lớp học
     */
    public function create()
    {
        try {
            $teachers = $this->teacherService->getTeachers();
            return view('classrooms.create', compact('teachers'));
        } catch (\Exception $e) {
            \Log::error('Error loading create form: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Lỗi khi tải form');
        }
    }

    /**
     * Lưu lớp học mới
     */
    public function store(StoreClassroomRequest $request)
    {
        try {
            $classroom = $this->classroomService->createClassroom($request->validated());
            return redirect()->route('classrooms.show', $classroom)
                            ->with('success', 'Thêm lớp học thành công');
        } catch (\Exception $e) {
            \Log::error('Error creating classroom: ' . $e->getMessage());
            return redirect()->back()
                            ->with('error', 'Lỗi khi thêm lớp học: ' . $e->getMessage())
                            ->withInput();
        }
    }

    /**
     * Xem chi tiết lớp học
     */
    public function show($id)
    {
        try {
            $classroom = $this->classroomService->getClassroomDetail($id);
            return view('classrooms.show', compact('classroom'));
        } catch (\Exception $e) {
            \Log::error('Error fetching classroom: ' . $e->getMessage());
            return redirect()->route('classrooms.index')
                            ->with('error', 'Lớp học không tồn tại');
        }
    }

    /**
     * Form sửa lớp học
     */
    public function edit($id)
    {
        try {
            $classroom = $this->classroomService->getClassroomDetail($id);
            $teachers = $this->teacherService->getTeachers();
            return view('classrooms.edit', compact('classroom', 'teachers'));
        } catch (\Exception $e) {
            \Log::error('Error loading edit form: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Lỗi khi tải form');
        }
    }

    /**
     * Cập nhật lớp học
     */
    public function update(StoreClassroomRequest $request, $id)
    {
        try {
            $classroom = $this->classroomService->updateClassroom($id, $request->validated());
            return redirect()->route('classrooms.show', $classroom)
                            ->with('success', 'Cập nhật lớp học thành công');
        } catch (\Exception $e) {
            \Log::error('Error updating classroom: ' . $e->getMessage());
            return redirect()->back()
                            ->with('error', 'Lỗi khi cập nhật lớp học: ' . $e->getMessage())
                            ->withInput();
        }
    }

    /**
     * Xóa lớp học
     */
    public function destroy($id)
    {
        try {
            $this->classroomService->deleteClassroom($id);
            return redirect()->route('classrooms.index')
                            ->with('success', 'Xóa lớp học thành công');
        } catch (\Exception $e) {
            \Log::error('Error deleting classroom: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Lỗi khi xóa lớp học');
        }
    }

    /**
     * Khôi phục lớp học bị xóa
     */
    public function restore($id)
    {
        try {
            $this->classroomService->restoreClassroom($id);
            return redirect()->route('classrooms.index')
                            ->with('success', 'Khôi phục lớp học thành công');
        } catch (\Exception $e) {
            \Log::error('Error restoring classroom: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Lỗi khi khôi phục lớp học');
        }
    }
}
