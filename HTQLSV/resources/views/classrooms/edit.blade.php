@extends('layouts.master')

@section('title', 'Sửa Lớp Học')

@section('content')
<div class="row">
    <div class="col-md-8 offset-md-2">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Sửa Thông Tin Lớp Học</h5>
            </div>
            <div class="card-body">
                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Lỗi:</strong>
                        <ul class="mb-0 mt-2">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <form action="{{ route('classrooms.update', $classroom) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label">Mã Lớp</label>
                        <input type="text" name="classroom_code" class="form-control @error('classroom_code') is-invalid @enderror" 
                               value="{{ old('classroom_code', $classroom->classroom_code) }}" required>
                        @error('classroom_code')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Tên Lớp</label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                               value="{{ old('name', $classroom->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Năm Học</label>
                        <input type="text" name="academic_year" class="form-control @error('academic_year') is-invalid @enderror" 
                               value="{{ old('academic_year', $classroom->academic_year) }}" required>
                        @error('academic_year')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Giáo Viên Chủ Nhiệm</label>
                        <select name="teacher_id" class="form-select @error('teacher_id') is-invalid @enderror" required>
                            <option value="">-- Chọn giáo viên --</option>
                            @foreach($teachers as $teacher)
                                <option value="{{ $teacher->id }}" {{ old('teacher_id', $classroom->teacher_id) == $teacher->id ? 'selected' : '' }}>
                                    {{ $teacher->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('teacher_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Sức Chứa</label>
                        <input type="number" name="capacity" class="form-control @error('capacity') is-invalid @enderror" 
                               value="{{ old('capacity', $classroom->capacity) }}" required>
                        @error('capacity')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg"></i> Lưu Thay Đổi
                        </button>
                        <a href="{{ route('classrooms.show', $classroom) }}" class="btn btn-secondary">
                            <i class="bi bi-x-lg"></i> Hủy
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
