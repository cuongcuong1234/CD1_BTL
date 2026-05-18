@extends('layouts.master')

@section('title', 'Chi Tiết Điểm')

@section('content')
<div class="row">
    <div class="col-md-6 offset-md-3">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Chi Tiết Điểm</h5>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Sinh Viên:</strong>
                        <a href="{{ route('students.show', $grade->student) }}">
                            {{ $grade->student->name }}
                        </a>
                    </div>
                    <div class="col-md-6">
                        <strong>Môn Học:</strong>
                        <a href="{{ route('subjects.show', $grade->subject) }}">
                            {{ $grade->subject->name }}
                        </a>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Điểm:</strong> {{ $grade->score }}/10
                    </div>
                    <div class="col-md-6">
                        <strong>Hạng:</strong> <span class="badge bg-info">{{ $grade->grade_letter }}</span>
                    </div>
                </div>

                <hr>

                <div class="d-flex gap-2">
                    <a href="{{ route('grades.edit', $grade) }}" class="btn btn-warning">
                        <i class="bi bi-pencil"></i> Sửa
                    </a>
                    <form action="{{ route('grades.destroy', $grade) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" onclick="return confirm('Bạn chắc chắn muốn xóa?')">
                            <i class="bi bi-trash"></i> Xóa
                        </button>
                    </form>
                    <a href="{{ route('grades.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Quay Lại
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
