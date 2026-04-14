@extends('layouts.master')

@section('title', 'Thêm Môn Học')

@section('content')
<div class="row">
    <div class="col-md-8 offset-md-2">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Thêm Môn Học Mới</h5>
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

                <form action="{{ route('subjects.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Mã Môn Học</label>
                        <input type="text" name="subject_code" class="form-control @error('subject_code') is-invalid @enderror" 
                               value="{{ old('subject_code') }}" required>
                        @error('subject_code')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Tên Môn Học</label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                               value="{{ old('name') }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Mô Tả</label>
                        <textarea name="description" class="form-control @error('description') is-invalid @enderror" 
                                  rows="4">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Tín Chỉ</label>
                        <input type="number" name="credits" class="form-control @error('credits') is-invalid @enderror" 
                               value="{{ old('credits', 3) }}" min="1" required>
                        @error('credits')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg"></i> Thêm Môn Học
                        </button>
                        <a href="{{ route('subjects.index') }}" class="btn btn-secondary">
                            <i class="bi bi-x-lg"></i> Hủy
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
