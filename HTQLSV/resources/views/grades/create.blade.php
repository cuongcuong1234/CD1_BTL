@extends('layouts.master')

@section('title', 'Thêm/Sửa Điểm')

@section('content')
<div class="row">
    <div class="col-md-6 offset-md-3">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Thêm/Sửa Điểm</h5>
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

                <form action="{{ route('grades.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Sinh Viên</label>
                        <select name="student_id" class="form-select @error('student_id') is-invalid @enderror" required>
                            <option value="">-- Chọn sinh viên --</option>
                            @foreach($students as $student)
                                <option value="{{ $student->id }}" {{ old('student_id') == $student->id ? 'selected' : '' }}>
                                    {{ $student->name }} ({{ $student->student_code }})
                                </option>
                            @endforeach
                        </select>
                        @error('student_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Môn Học</label>
                        <select name="subject_id" class="form-select @error('subject_id') is-invalid @enderror" required>
                            <option value="">-- Chọn môn học --</option>
                            @foreach($subjects as $subject)
                                <option value="{{ $subject->id }}" {{ old('subject_id') == $subject->id ? 'selected' : '' }}>
                                    {{ $subject->name }} ({{ $subject->subject_code }})
                                </option>
                            @endforeach
                        </select>
                        @error('subject_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Điểm (0-10)</label>
                        <input type="number" name="score" class="form-control @error('score') is-invalid @enderror" 
                               value="{{ old('score') }}" min="0" max="10" step="0.1" required>
                        @error('score')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg"></i> Lưu Điểm
                        </button>
                        <a href="{{ route('grades.index') }}" class="btn btn-secondary">
                            <i class="bi bi-x-lg"></i> Hủy
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
