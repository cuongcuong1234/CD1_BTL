<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Classroom;
use App\Http\Requests\StoreStudentRequest;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    // Hiển thị danh sách sinh viên
    public function index(Request $request)
    {
        $query = Student::with('classroom');
        
        // Tìm kiếm
        if ($request->search) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%')
                  ->orWhere('student_code', 'like', '%' . $request->search . '%');
        }
        
        // Lọc theo trạng thái
        if ($request->status) {
            $query->where('status', $request->status);
        }
        
        // Lọc theo lớp
        if ($request->classroom_id) {
            $query->where('classroom_id', $request->classroom_id);
        }
        
        // Sắp xếp
        $sort = $request->sort ?? 'created_at';
        $order = $request->order ?? 'desc';
        $query->orderBy($sort, $order);
        
        $students = $query->paginate(10);
        $classrooms = Classroom::all();
        
        return view('students.index', compact('students', 'classrooms'));
    }
    
    // Form thêm sinh viên
    public function create()
    {
        $classrooms = Classroom::all();
        return view('students.create', compact('classrooms'));
    }
    
    // Lưu sinh viên mới
    public function store(StoreStudentRequest $request)
    {
        $student = Student::create($request->validated());
        return redirect()->route('students.show', $student)
                        ->with('success', 'Thêm sinh viên thành công');
    }
    
    // Xem chi tiết sinh viên
    public function show(Student $student)
    {
        $student->load('classroom', 'grades.subject', 'enrollments.subject');
        return view('students.show', compact('student'));
    }
    
    // Form sửa sinh viên
    public function edit(Student $student)
    {
        $classrooms = Classroom::all();
        return view('students.edit', compact('student', 'classrooms'));
    }
    
    // Cập nhật sinh viên
    public function update(StoreStudentRequest $request, Student $student)
    {
        $student->update($request->validated());
        return redirect()->route('students.show', $student)
                        ->with('success', 'Cập nhật sinh viên thành công');
    }
    
    // Xóa sinh viên (soft delete)
    public function destroy(Student $student)
    {
        $student->delete();
        return redirect()->route('students.index')
                        ->with('success', 'Xóa sinh viên thành công');
    }
    
    // Khôi phục sinh viên đã xóa
    public function restore($id)
    {
        $student = Student::onlyTrashed()->findOrFail($id);
        $student->restore();
        return redirect()->route('students.index')
                        ->with('success', 'Khôi phục sinh viên thành công');
    }
    
    // Danh sách sinh viên đã xóa
    public function trashed()
    {
        $students = Student::onlyTrashed()->paginate(10);
        return view('students.trashed', compact('students'));
    }
}
