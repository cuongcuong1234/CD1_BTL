@extends('layouts.master')

@section('title', 'Chi Tiết Đăng Ký')

@section('content')
<div class="row">
    <div class="col-md-6 offset-md-3">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Chi Tiết Đăng Ký</h5>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-12">
                        <strong>Sinh Viên:</strong>
                        <a href="{{ route('students.show', $enrollment->student) }}">
                            {{ $enrollment->student->name }}
                        </a>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-12">
                        <strong>Môn Học:</strong>
                        <a href="{{ route('subjects.show', $enrollment->subject) }}">
                            {{ $enrollment->subject->name }}
                        </a>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Ngày Đăng Ký:</strong>
                        {{ $enrollment->enrollment_date->format('d/m/Y') }}
                    </div>
                    <div class="col-md-6">
                        <strong>Trạng Thái:</strong>
                        @if($enrollment->status == 'enrolled')
                            <span class="badge bg-success">Đã đăng ký</span>
                        @elseif($enrollment->status == 'completed')
                            <span class="badge bg-info">Hoàn tất</span>
                        @else
                            <span class="badge bg-danger">Rút khỏi</span>
                        @endif
                    </div>
                </div>

                <hr>

                <div class="d-flex gap-2">
                    <a href="{{ route('enrollments.edit', $enrollment) }}" class="btn btn-warning">
                        <i class="bi bi-pencil"></i> Sửa
                    </a>
                    <form action="{{ route('enrollments.destroy', $enrollment) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" onclick="return confirm('Bạn chắc chắn muốn xóa?')">
                            <i class="bi bi-trash"></i> Xóa
                        </button>
                    </form>
                    <a href="{{ route('enrollments.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Quay Lại
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
