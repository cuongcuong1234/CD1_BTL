<?php

namespace App\Http\Controllers;

use App\Models\Enrollment;
use App\Models\Student;
use App\Models\Subject;
use Illuminate\Http\Request;

class EnrollmentController extends Controller
{
    public function index(Request $request)
    {
        $query = Enrollment::with('student', 'subject');
        
        if ($request->student_id) {
            $query->where('student_id', $request->student_id);
        }
        
        if ($request->subject_id) {
            $query->where('subject_id', $request->subject_id);
        }
        
        if ($request->status) {
            $query->where('status', $request->status);
        }
        
        $enrollments = $query->orderBy('enrollment_date', 'desc')->paginate(15);
        $students = Student::all();
        $subjects = Subject::all();
        
        return view('enrollments.index', compact('enrollments', 'students', 'subjects'));
    }
    
    public function create()
    {
        $students = Student::all();
        $subjects = Subject::all();
        return view('enrollments.create', compact('students', 'subjects'));
    }

    public function show(Enrollment $enrollment)
    {
        $enrollment->load('student', 'subject');
        return view('enrollments.show', compact('enrollment'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'subject_id' => 'required|exists:subjects,id',
            'enrollment_date' => 'required|date',
            'status' => 'required|in:enrolled,completed,dropped',
        ]);
        
        // Kiểm tra đăng ký đã tồn tại chưa
        $exists = Enrollment::where('student_id', $request->student_id)
                            ->where('subject_id', $request->subject_id)
                            ->exists();
        
        if ($exists) {
            return redirect()->back()
                           ->withErrors('Sinh viên đã đăng ký môn học này')
                           ->withInput();
        }
        
        Enrollment::create($request->validated());
        return redirect()->route('enrollments.index')
                        ->with('success', 'Đăng ký môn học thành công');
    }
    
    public function edit(Enrollment $enrollment)
    {
        $students = Student::all();
        $subjects = Subject::all();
        return view('enrollments.edit', compact('enrollment', 'students', 'subjects'));
    }
    
    public function update(Request $request, Enrollment $enrollment)
    {
        $request->validate([
            'status' => 'required|in:enrolled,completed,dropped',
        ]);
        
        $enrollment->update($request->validated());
        return redirect()->route('enrollments.index')
                        ->with('success', 'Cập nhật đăng ký thành công');
    }
    
    public function destroy(Enrollment $enrollment)
    {
        $enrollment->delete();
        return redirect()->route('enrollments.index')
                        ->with('success', 'Xóa đăng ký thành công');
    }
}
