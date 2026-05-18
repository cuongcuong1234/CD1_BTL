<?php

namespace App\Http\Controllers;

use App\Services\GradeService;
use App\Services\StudentService;
use App\Services\SubjectService;
use Illuminate\Http\Request;

class GradeController extends Controller
{
    protected $gradeService;
    protected $studentService;
    protected $subjectService;

    public function __construct(
        GradeService $gradeService,
        StudentService $studentService,
        SubjectService $subjectService
    ) {
        $this->gradeService = $gradeService;
        $this->studentService = $studentService;
        $this->subjectService = $subjectService;
    }

    /**
     * Hiển thị danh sách điểm
     */
    public function index(Request $request)
    {
        try {
            $studentId = $request->student_id ?? null;
            $subjectId = $request->subject_id ?? null;

            $grades = $this->gradeService->getGrades($studentId, $subjectId);
            $students = $this->studentService->getStudents();
            $subjects = $this->subjectService->getSubjects();

            return view('grades.index', compact('grades', 'students', 'subjects'));
        } catch (\Exception $e) {
            \Log::error('Error fetching grades: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Lỗi khi tải danh sách điểm');
        }
    }

    /**
     * Form thêm điểm
     */
    public function create()
    {
        try {
            $students = $this->studentService->getStudents();
            $subjects = $this->subjectService->getSubjects();
            return view('grades.create', compact('students', 'subjects'));
        } catch (\Exception $e) {
            \Log::error('Error loading create form: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Lỗi khi tải form');
        }
    }

    /**
     * Xem chi tiết điểm
     */
    public function show($id)
    {
        try {
            $grade = $this->gradeService->getGradeDetail($id);
            return view('grades.show', compact('grade'));
        } catch (\Exception $e) {
            \Log::error('Error fetching grade: ' . $e->getMessage());
            return redirect()->route('grades.index')
                            ->with('error', 'Điểm không tồn tại');
        }
    }

    /**
     * Lưu điểm mới
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'student_id' => 'required|exists:students,id',
                'subject_id' => 'required|exists:subjects,id',
                'score' => 'required|numeric|min:0|max:10',
            ]);

            $this->gradeService->createOrUpdateGrade($request->all());

            return redirect()->route('grades.index')
                            ->with('success', 'Thêm/cập nhật điểm thành công');
        } catch (\Exception $e) {
            \Log::error('Error creating grade: ' . $e->getMessage());
            return redirect()->back()
                            ->with('error', 'Lỗi khi thêm điểm: ' . $e->getMessage())
                            ->withInput();
        }
    }

    /**
     * Form sửa điểm
     */
    public function edit($id)
    {
        try {
            $grade = $this->gradeService->getGradeDetail($id);
            $students = $this->studentService->getStudents();
            $subjects = $this->subjectService->getSubjects();
            return view('grades.edit', compact('grade', 'students', 'subjects'));
        } catch (\Exception $e) {
            \Log::error('Error loading edit form: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Lỗi khi tải form');
        }
    }

    /**
     * Cập nhật điểm
     */
    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'score' => 'required|numeric|min:0|max:10',
            ]);

            $this->gradeService->updateGrade($id, $request->all());

            return redirect()->route('grades.index')
                            ->with('success', 'Cập nhật điểm thành công');
        } catch (\Exception $e) {
            \Log::error('Error updating grade: ' . $e->getMessage());
            return redirect()->back()
                            ->with('error', 'Lỗi khi cập nhật điểm: ' . $e->getMessage())
                            ->withInput();
        }
    }

    /**
     * Xóa điểm
     */
    public function destroy($id)
    {
        try {
            $this->gradeService->deleteGrade($id);
            return redirect()->route('grades.index')
                            ->with('success', 'Xóa điểm thành công');
        } catch (\Exception $e) {
            \Log::error('Error deleting grade: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Lỗi khi xóa điểm');
        }
    }
}
