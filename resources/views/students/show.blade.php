@extends('layouts.master')

@section('title', 'Chi Tiết Sinh Viên')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card mb-3">
            <div class="card-header">
                <h5 class="mb-0">Thông Tin Sinh Viên</h5>
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Mã Sinh Viên:</strong> {{ $student->student_code }}
                    </div>
                    <div class="col-md-6">
                        <strong>Trạng Thái:</strong>
                        @if($student->status == 'active')
                            <span class="badge bg-success">Hoạt động</span>
                        @else
                            <span class="badge bg-secondary">Không hoạt động</span>
                        @endif
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Họ Tên:</strong> {{ $student->name }}
                    </div>
                    <div class="col-md-6">
                        <strong>Email:</strong> {{ $student->email }}
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Điện Thoại:</strong> {{ $student->phone ?? 'N/A' }}
                    </div>
                    <div class="col-md-6">
                        <strong>Địa Chỉ:</strong> {{ $student->address ?? 'N/A' }}
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Lớp:</strong> {{ $student->classroom->name ?? 'N/A' }}
                    </div>
                    <div class="col-md-6">
                        <strong>Năm Học:</strong> {{ $student->classroom->academic_year ?? 'N/A' }}
                    </div>
                </div>

                <hr>

                <div class="d-flex gap-2">
                    <a href="{{ route('students.edit', $student) }}" class="btn btn-warning">
                        <i class="bi bi-pencil"></i> Sửa
                    </a>
                    <form action="{{ route('students.destroy', $student) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" onclick="return confirm('Bạn chắc chắn muốn xóa?')">
                            <i class="bi bi-trash"></i> Xóa
                        </button>
                    </form>
                    <a href="{{ route('students.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Quay Lại
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <!-- Grades Card -->
        <div class="card mb-3">
            <div class="card-header">
                <h6 class="mb-0">Điểm Số</h6>
            </div>
            <div class="card-body">
                @if($student->grades->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Môn</th>
                                    <th>Điểm</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($student->grades as $grade)
                                    <tr>
                                        <td>{{ $grade->subject->name ?? 'N/A' }}</td>
                                        <td>
                                            {{ $grade->score }}
                                            <span class="badge bg-info badge-status">{{ $grade->grade_letter }}</span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted">Chưa có điểm số</p>
                @endif
            </div>
        </div>

        <!-- Enrollments Card -->
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">Môn Đã Đăng Ký</h6>
            </div>
            <div class="card-body">
                @if($student->enrollments->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($student->enrollments as $enrollment)
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                {{ $enrollment->subject->name ?? 'N/A' }}
                                <span class="badge bg-primary rounded-pill">
                                    @if($enrollment->status == 'enrolled')
                                        Đã đăng ký
                                    @else
                                        Chấp nhận
                                    @endif
                                </span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-muted">Chưa đăng ký môn nào</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
