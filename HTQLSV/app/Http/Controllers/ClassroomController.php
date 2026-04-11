<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
use App\Models\Teacher;
use App\Http\Requests\StoreClassroomRequest;
use Illuminate\Http\Request;

class ClassroomController extends Controller
{
    public function index(Request $request)
    {
        $query = Classroom::with('teacher', 'students');
        
        if ($request->search) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('classroom_code', 'like', '%' . $request->search . '%');
        }
        
        if ($request->academic_year) {
            $query->where('academic_year', $request->academic_year);
        }
        
        $classrooms = $query->orderBy('created_at', 'desc')->paginate(10);
        return view('classrooms.index', compact('classrooms'));
    }
    
    public function create()
    {
        $teachers = Teacher::all();
        return view('classrooms.create', compact('teachers'));
    }
    
    public function store(StoreClassroomRequest $request)
    {
        $classroom = Classroom::create($request->validated());
        return redirect()->route('classrooms.show', $classroom)
                        ->with('success', 'Thêm lớp học thành công');
    }
    
    public function show(Classroom $classroom)
    {
        $classroom->load('teacher', 'students');
        return view('classrooms.show', compact('classroom'));
    }
    
    public function edit(Classroom $classroom)
    {
        $teachers = Teacher::all();
        return view('classrooms.edit', compact('classroom', 'teachers'));
    }
    
    public function update(StoreClassroomRequest $request, Classroom $classroom)
    {
        $classroom->update($request->validated());
        return redirect()->route('classrooms.show', $classroom)
                        ->with('success', 'Cập nhật lớp học thành công');
    }
    
    public function destroy(Classroom $classroom)
    {
        $classroom->delete();
        return redirect()->route('classrooms.index')
                        ->with('success', 'Xóa lớp học thành công');
    }
    
    public function restore($id)
    {
        $classroom = Classroom::onlyTrashed()->findOrFail($id);
        $classroom->restore();
        return redirect()->route('classrooms.index')
                        ->with('success', 'Khôi phục lớp học thành công');
    }
}
