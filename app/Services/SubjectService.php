<?php

namespace App\Services;

use App\Repositories\SubjectRepository;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class SubjectService
{
    protected $subjectRepository;

    public function __construct(SubjectRepository $subjectRepository)
    {
        $this->subjectRepository = $subjectRepository;
    }

    /**
     * Lấy danh sách môn học
     */
    public function getSubjects($search = null, $perPage = 15)
    {
        return $this->subjectRepository->getSubjectsList($search, $perPage);
    }

    /**
     * Lấy chi tiết môn học
     */
    public function getSubjectDetail($id)
    {
        $cacheKey = "subject_{$id}";
        
        return Cache::remember($cacheKey, 3600, function () use ($id) {
            return $this->subjectRepository->findOrFail($id, ['*'], ['grades']);
        });
    }

    /**
     * Tạo môn học mới
     */
    public function createSubject(array $data)
    {
        try {
            DB::beginTransaction();

            $subject = $this->subjectRepository->create($data);

            Cache::forget('subjects_list');

            DB::commit();

            return $subject;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Cập nhật môn học
     */
    public function updateSubject($id, array $data)
    {
        try {
            DB::beginTransaction();

            $subject = $this->subjectRepository->update($id, $data);

            Cache::forget("subject_{$id}");
            Cache::forget('subjects_list');

            DB::commit();

            return $subject;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Xóa môn học
     */
    public function deleteSubject($id)
    {
        try {
            DB::beginTransaction();

            $this->subjectRepository->delete($id);

            Cache::forget("subject_{$id}");
            Cache::forget('subjects_list');

            DB::commit();

            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Lấy danh sách môn học có giáo viên
     */
    public function getSubjectsWithTeachers()
    {
        $cacheKey = 'subjects_with_teachers';
        
        return Cache::remember($cacheKey, 3600, function () {
            return $this->subjectRepository->getSubjectsWithTeachers();
        });
    }

    /**
     * Khôi phục môn học bị xóa
     */
    public function restoreSubject($id)
    {
        try {
            DB::beginTransaction();

            $subject = $this->subjectRepository->restore($id);

            Cache::forget("subject_{$id}");
            Cache::forget('subjects_list');

            DB::commit();

            return $subject;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Lấy danh sách môn học bị xóa
     */
    public function getTrashedSubjects()
    {
        return $this->subjectRepository->getTrashed();
    }
}
