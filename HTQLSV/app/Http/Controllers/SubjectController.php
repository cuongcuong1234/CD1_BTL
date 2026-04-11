<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use App\Http\Requests\StoreSubjectRequest;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    public function index(Request $request)
    {
        $query = Subject::query();
        
        if ($request->search) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('subject_code', 'like', '%' . $request->search . '%');
        }
        
        if ($request->credits) {
            $query->where('credits', $request->credits);
        }
        
        $subjects = $query->withCount('enrollments')
                         ->orderBy('created_at', 'desc')
                         ->paginate(10);
        return view('subjects.index', compact('subjects'));
    }
    
    public function create()
    {
        return view('subjects.create');
    }
    
    public function store(StoreSubjectRequest $request)
    {
        $subject = Subject::create($request->validated());
        return redirect()->route('subjects.show', $subject)
                        ->with('success', 'Thêm môn học thành công');
    }
    
    public function show(Subject $subject)
    {
        $subject->load('enrollments.student', 'grades.student');
        return view('subjects.show', compact('subject'));
    }
    
    public function edit(Subject $subject)
    {
        return view('subjects.edit', compact('subject'));
    }
    
    public function update(StoreSubjectRequest $request, Subject $subject)
    {
        $subject->update($request->validated());
        return redirect()->route('subjects.show', $subject)
                        ->with('success', 'Cập nhật môn học thành công');
    }
    
    public function destroy(Subject $subject)
    {
        $subject->delete();
        return redirect()->route('subjects.index')
                        ->with('success', 'Xóa môn học thành công');
    }
    
    public function restore($id)
    {
        $subject = Subject::onlyTrashed()->findOrFail($id);
        $subject->restore();
        return redirect()->route('subjects.index')
                        ->with('success', 'Khôi phục môn học thành công');
    }
}
