<?php

namespace App\Http\Controllers;

use App\Models\Grade;
use App\Models\Student;
use App\Models\Subject;
use Illuminate\Http\Request;

class GradeController extends Controller
{
    public function index(Request $request)
    {
        $query = Grade::with('student', 'subject');
        
        if ($request->student_id) {
            $query->where('student_id', $request->student_id);
        }
        
        if ($request->subject_id) {
            $query->where('subject_id', $request->subject_id);
        }
        
        $grades = $query->orderBy('created_at', 'desc')->paginate(20);
        $students = Student::all();
        $subjects = Subject::all();
        
        return view('grades.index', compact('grades', 'students', 'subjects'));
    }
    
    public function create()
    {
        $students = Student::all();
        $subjects = Subject::all();
        return view('grades.create', compact('students', 'subjects'));
    }

    public function show(Grade $grade)
    {
        $grade->load('student', 'subject');
        return view('grades.show', compact('grade'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'subject_id' => 'required|exists:subjects,id',
            'score' => 'required|numeric|min:0|max:10',
        ]);
        
        $score = $request->score;
        $grade_letter = $this->getGradeLetter($score);
        
        $grade = Grade::updateOrCreate(
            ['student_id' => $request->student_id, 'subject_id' => $request->subject_id],
            ['score' => $score, 'grade_letter' => $grade_letter]
        );
        
        return redirect()->route('grades.index')
                        ->with('success', 'Thêm/cập nhật điểm thành công');
    }
    
    public function edit(Grade $grade)
    {
        $students = Student::all();
        $subjects = Subject::all();
        return view('grades.edit', compact('grade', 'students', 'subjects'));
    }
    
    public function update(Request $request, Grade $grade)
    {
        $request->validate([
            'score' => 'required|numeric|min:0|max:10',
        ]);
        
        $score = $request->score;
        $grade_letter = $this->getGradeLetter($score);
        
        $grade->update([
            'score' => $score,
            'grade_letter' => $grade_letter
        ]);
        
        return redirect()->route('grades.index')
                        ->with('success', 'Cập nhật điểm thành công');
    }
    
    public function destroy(Grade $grade)
    {
        $grade->delete();
        return redirect()->route('grades.index')
                        ->with('success', 'Xóa điểm thành công');
    }
    
    // Chuyển điểm số thành điểm chữ
    private function getGradeLetter($score)
    {
        if ($score >= 8.5) return 'A';
        if ($score >= 7.0) return 'B';
        if ($score >= 5.5) return 'C';
        if ($score >= 4.0) return 'D';
        return 'F';
    }
}
