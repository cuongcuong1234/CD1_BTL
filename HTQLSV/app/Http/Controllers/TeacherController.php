<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use App\Http\Requests\StoreTeacherRequest;
use Illuminate\Http\Request;

class TeacherController extends Controller
{
    public function index(Request $request)
    {
        $query = Teacher::query();
        
        if ($request->search) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%')
                  ->orWhere('teacher_code', 'like', '%' . $request->search . '%');
        }
        
        if ($request->status) {
            $query->where('status', $request->status);
        }
        
        $teachers = $query->with('classrooms')->orderBy('created_at', 'desc')->paginate(10);
        return view('teachers.index', compact('teachers'));
    }
    
    public function create()
    {
        return view('teachers.create');
    }
    
    public function store(StoreTeacherRequest $request)
    {
        $teacher = Teacher::create($request->validated());
        return redirect()->route('teachers.show', $teacher)
                        ->with('success', 'Thêm giáo viên thành công');
    }
    
    public function show(Teacher $teacher)
    {
        $teacher->load('classrooms.students');
        return view('teachers.show', compact('teacher'));
    }
    
    public function edit(Teacher $teacher)
    {
        return view('teachers.edit', compact('teacher'));
    }
    
    public function update(StoreTeacherRequest $request, Teacher $teacher)
    {
        $teacher->update($request->validated());
        return redirect()->route('teachers.show', $teacher)
                        ->with('success', 'Cập nhật giáo viên thành công');
    }
    
    public function destroy(Teacher $teacher)
    {
        $teacher->delete();
        return redirect()->route('teachers.index')
                        ->with('success', 'Xóa giáo viên thành công');
    }
    
    public function restore($id)
    {
        $teacher = Teacher::onlyTrashed()->findOrFail($id);
        $teacher->restore();
        return redirect()->route('teachers.index')
                        ->with('success', 'Khôi phục giáo viên thành công');
    }
}
