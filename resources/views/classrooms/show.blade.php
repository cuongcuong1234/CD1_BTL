@extends('layouts.master')

@section('title', 'Chi Tiết Lớp Học')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card mb-3">
            <div class="card-header">
                <h5 class="mb-0">Thông Tin Lớp Học</h5>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Mã Lớp:</strong> {{ $classroom->classroom_code }}
                    </div>
                    <div class="col-md-6">
                        <strong>Tên Lớp:</strong> {{ $classroom->name }}
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Năm Học:</strong> {{ $classroom->academic_year }}
                    </div>
                    <div class="col-md-6">
                        <strong>Sức Chứa:</strong> {{ $classroom->capacity }} học sinh
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-12">
                        <strong>Giáo Viên Chủ Nhiệm:</strong> {{ $classroom->teacher->name ?? 'N/A' }}
                    </div>
                </div>

                <hr>

                <div class="d-flex gap-2">
                    <a href="{{ route('classrooms.edit', $classroom) }}" class="btn btn-warning">
                        <i class="bi bi-pencil"></i> Sửa
                    </a>
                    <form action="{{ route('classrooms.destroy', $classroom) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" onclick="return confirm('Bạn chắc chắn muốn xóa?')">
                            <i class="bi bi-trash"></i> Xóa
                        </button>
                    </form>
                    <a href="{{ route('classrooms.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Quay Lại
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">Danh Sách Sinh Viên ({{ $classroom->students->count() }})</h6>
            </div>
            <div class="card-body" style="max-height: 400px; overflow-y: auto;">
                @if($classroom->students->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($classroom->students as $student)
                            <a href="{{ route('students.show', $student) }}" class="list-group-item list-group-item-action">
                                {{ $student->name }}
                                <small class="text-muted d-block">{{ $student->student_code }}</small>
                            </a>
                        @endforeach
                    </div>
                @else
                    <p class="text-muted">Chưa có sinh viên</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
