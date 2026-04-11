<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Classroom;
use App\Models\Teacher;
use App\Models\Subject;
use App\Models\Enrollment;
use App\Models\Grade;

class DashboardController extends Controller
{
    public function index()
    {
        // Thống kê chung
        $totalStudents = Student::count();
        $totalClassrooms = Classroom::count();
        $totalTeachers = Teacher::count();
        $totalSubjects = Subject::count();
        
        // Sinh viên thêm trong 30 ngày qua
        $newStudentsThisMonth = Student::whereDate('created_at', '>=', now()->subDays(30))->count();
        
        // Lớp có sinh viên nhiều nhất
        $classroomMostStudents = Classroom::withCount('students')
                                          ->orderBy('students_count', 'desc')
                                          ->first();
        
        // Sinh viên mới nhất
        $recentStudents = Student::latest()
                                 ->with('classroom')
                                 ->limit(5)
                                 ->get();
        
        // Thống kê đăng ký
        $totalEnrollments = Enrollment::count();
        $averageGrade = Grade::whereNotNull('score')
                            ->average('score');
        
        // Top 5 sinh viên có điểm trung bình cao nhất
        $topStudents = Student::withCount('enrollments')
                             ->with('grades')
                             ->orderByDesc(
                                Grade::selectRaw('AVG(score)')
                                    ->whereColumn('student_id', 'students.id')
                             )
                             ->limit(5)
                             ->get();
        
        return view('dashboard', compact(
            'totalStudents',
            'totalClassrooms', 
            'totalTeachers',
            'totalSubjects',
            'newStudentsThisMonth',
            'classroomMostStudents',
            'recentStudents',
            'totalEnrollments',
            'averageGrade',
            'topStudents'
        ));
    }
}
