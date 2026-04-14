@extends('layouts.master')

@section('title', 'Thêm Đăng Ký')

@section('content')
<div class="row">
    <div class="col-md-6 offset-md-3">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Thêm Đăng Ký Môn Học</h5>
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

                <form action="{{ route('enrollments.store') }}" method="POST">
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
                        <label class="form-label">Ngày Đăng Ký</label>
                        <input type="date" name="enrollment_date" class="form-control @error('enrollment_date') is-invalid @enderror" 
                               value="{{ old('enrollment_date', now()->format('Y-m-d')) }}" required>
                        @error('enrollment_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Trạng Thái</label>
                        <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                            <option value="enrolled" {{ old('status') === 'enrolled' ? 'selected' : '' }}>Đã đăng ký</option>
                            <option value="completed" {{ old('status') === 'completed' ? 'selected' : '' }}>Hoàn tất</option>
                            <option value="dropped" {{ old('status') === 'dropped' ? 'selected' : '' }}>Rút khỏi</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg"></i> Thêm Đăng Ký
                        </button>
                        <a href="{{ route('enrollments.index') }}" class="btn btn-secondary">
                            <i class="bi bi-x-lg"></i> Hủy
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
