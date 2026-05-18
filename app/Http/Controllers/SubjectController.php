<?php

namespace App\Http\Controllers;

use App\Services\SubjectService;
use App\Http\Requests\StoreSubjectRequest;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    protected $subjectService;

    public function __construct(SubjectService $subjectService)
    {
        $this->subjectService = $subjectService;
    }

    /**
     * Hiển thị danh sách môn học
     */
    public function index(Request $request)
    {
        try {
            $search = $request->search ?? null;
            $subjects = $this->subjectService->getSubjects($search);
            return view('subjects.index', compact('subjects'));
        } catch (\Exception $e) {
            \Log::error('Error fetching subjects: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Lỗi khi tải danh sách môn học');
        }
    }

    /**
     * Form thêm môn học
     */
    public function create()
    {
        return view('subjects.create');
    }

    /**
     * Lưu môn học mới
     */
    public function store(StoreSubjectRequest $request)
    {
        try {
            $subject = $this->subjectService->createSubject($request->validated());
            return redirect()->route('subjects.show', $subject)
                            ->with('success', 'Thêm môn học thành công');
        } catch (\Exception $e) {
            \Log::error('Error creating subject: ' . $e->getMessage());
            return redirect()->back()
                            ->with('error', 'Lỗi khi thêm môn học: ' . $e->getMessage())
                            ->withInput();
        }
    }

    /**
     * Xem chi tiết môn học
     */
    public function show($id)
    {
        try {
            $subject = $this->subjectService->getSubjectDetail($id);
            return view('subjects.show', compact('subject'));
        } catch (\Exception $e) {
            \Log::error('Error fetching subject: ' . $e->getMessage());
            return redirect()->route('subjects.index')
                            ->with('error', 'Môn học không tồn tại');
        }
    }

    /**
     * Form sửa môn học
     */
    public function edit($id)
    {
        try {
            $subject = $this->subjectService->getSubjectDetail($id);
            return view('subjects.edit', compact('subject'));
        } catch (\Exception $e) {
            \Log::error('Error loading edit form: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Lỗi khi tải form');
        }
    }

    /**
     * Cập nhật môn học
     */
    public function update(StoreSubjectRequest $request, $id)
    {
        try {
            $subject = $this->subjectService->updateSubject($id, $request->validated());
            return redirect()->route('subjects.show', $subject)
                            ->with('success', 'Cập nhật môn học thành công');
        } catch (\Exception $e) {
            \Log::error('Error updating subject: ' . $e->getMessage());
            return redirect()->back()
                            ->with('error', 'Lỗi khi cập nhật môn học: ' . $e->getMessage())
                            ->withInput();
        }
    }

    /**
     * Xóa môn học
     */
    public function destroy($id)
    {
        try {
            $this->subjectService->deleteSubject($id);
            return redirect()->route('subjects.index')
                            ->with('success', 'Xóa môn học thành công');
        } catch (\Exception $e) {
            \Log::error('Error deleting subject: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Lỗi khi xóa môn học');
        }
    }

    /**
     * Khôi phục môn học bị xóa
     */
    public function restore($id)
    {
        try {
            $this->subjectService->restoreSubject($id);
            return redirect()->route('subjects.index')
                            ->with('success', 'Khôi phục môn học thành công');
        } catch (\Exception $e) {
            \Log::error('Error restoring subject: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Lỗi khi khôi phục môn học');
        }
    }
}
