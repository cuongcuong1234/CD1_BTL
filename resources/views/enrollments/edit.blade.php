@extends('layouts.master')

@section('title', 'Sửa Đăng Ký')

@section('content')
<div class="row">
    <div class="col-md-6 offset-md-3">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Sửa Đăng Ký Môn Học</h5>
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

                <div class="mb-3">
                    <strong>Sinh Viên:</strong> {{ $enrollment->student->name }}
                </div>

                <div class="mb-3">
                    <strong>Môn Học:</strong> {{ $enrollment->subject->name }}
                </div>

                <form action="{{ route('enrollments.update', $enrollment) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label">Trạng Thái</label>
                        <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                            <option value="enrolled" {{ old('status', $enrollment->status) === 'enrolled' ? 'selected' : '' }}>Đã đăng ký</option>
                            <option value="completed" {{ old('status', $enrollment->status) === 'completed' ? 'selected' : '' }}>Hoàn tất</option>
                            <option value="dropped" {{ old('status', $enrollment->status) === 'dropped' ? 'selected' : '' }}>Rút khỏi</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg"></i> Lưu Thay Đổi
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
