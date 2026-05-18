@extends('layouts.master')

@section('title', 'Sửa Điểm')

@section('content')
<div class="row">
    <div class="col-md-6 offset-md-3">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Sửa Điểm</h5>
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
                    <strong>Sinh Viên:</strong> {{ $grade->student->name }}
                </div>

                <div class="mb-3">
                    <strong>Môn Học:</strong> {{ $grade->subject->name }}
                </div>

                <form action="{{ route('grades.update', $grade) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label">Điểm (0-10)</label>
                        <input type="number" name="score" class="form-control @error('score') is-invalid @enderror" 
                               value="{{ old('score', $grade->score) }}" min="0" max="10" step="0.1" required>
                        @error('score')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg"></i> Lưu Thay Đổi
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
