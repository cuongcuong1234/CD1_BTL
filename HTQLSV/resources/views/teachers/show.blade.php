@extends('layouts.master')

@section('title', 'Chi Tiết Giáo Viên')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card mb-3">
            <div class="card-header">
                <h5 class="mb-0">Thông Tin Giáo Viên</h5>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Mã Giáo Viên:</strong> {{ $teacher->teacher_code }}
                    </div>
                    <div class="col-md-6">
                        <strong>Trạng Thái:</strong>
                        @if($teacher->status == 'active')
                            <span class="badge bg-success">Hoạt động</span>
                        @else
                            <span class="badge bg-secondary">Không hoạt động</span>
                        @endif
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Họ Tên:</strong> {{ $teacher->name }}
                    </div>
                    <div class="col-md-6">
                        <strong>Email:</strong> {{ $teacher->email }}
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Điện Thoại:</strong> {{ $teacher->phone ?? 'N/A' }}
                    </div>
                    <div class="col-md-6">
                        <strong>Chuyên Môn:</strong> {{ $teacher->specialization ?? 'N/A' }}
                    </div>
                </div>

                <hr>

                <div class="d-flex gap-2">
                    <a href="{{ route('teachers.edit', $teacher) }}" class="btn btn-warning">
                        <i class="bi bi-pencil"></i> Sửa
                    </a>
                    <form action="{{ route('teachers.destroy', $teacher) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" onclick="return confirm('Bạn chắc chắn muốn xóa?')">
                            <i class="bi bi-trash"></i> Xóa
                        </button>
                    </form>
                    <a href="{{ route('teachers.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Quay Lại
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">Lớp Chủ Nhiệm</h6>
            </div>
            <div class="card-body">
                @if($teacher->classrooms->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($teacher->classrooms as $classroom)
                            <a href="{{ route('classrooms.show', $classroom) }}" class="list-group-item list-group-item-action">
                                {{ $classroom->name }}
                                <small class="text-muted d-block">{{ $classroom->academic_year }}</small>
                            </a>
                        @endforeach
                    </div>
                @else
                    <p class="text-muted">Chưa chủ nhiệm lớp nào</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
