@extends('layouts.master')

@section('title', 'Danh Sách Đăng Ký')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Danh Sách Đăng Ký Môn Học</h5>
        <a href="{{ route('enrollments.create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-lg"></i> Thêm đăng ký
        </a>
    </div>
    <div class="card-body">
        <form method="GET" class="row g-3 mb-4">
            <div class="col-md-3">
                <select name="student_id" class="form-select">
                    <option value="">-- Tất cả sinh viên --</option>
                    @foreach($students as $student)
                        <option value="{{ $student->id }}" {{ request('student_id') == $student->id ? 'selected' : '' }}>
                            {{ $student->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <select name="subject_id" class="form-select">
                    <option value="">-- Tất cả môn --</option>
                    @foreach($subjects as $subject)
                        <option value="{{ $subject->id }}" {{ request('subject_id') == $subject->id ? 'selected' : '' }}>
                            {{ $subject->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <select name="status" class="form-select">
                    <option value="">-- Tất cả trạng thái --</option>
                    <option value="enrolled" {{ request('status') == 'enrolled' ? 'selected' : '' }}>Đã đăng ký</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Hoàn tất</option>
                    <option value="dropped" {{ request('status') == 'dropped' ? 'selected' : '' }}>Rút khỏi</option>
                </select>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-outline-primary w-100">
                    <i class="bi bi-search"></i> Tìm
                </button>
            </div>
        </form>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Sinh Viên</th>
                        <th>Môn Học</th>
                        <th>Ngày Đăng Ký</th>
                        <th>Trạng Thái</th>
                        <th>Thao Tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($enrollments as $enrollment)
                        <tr>
                            <td>
                                <a href="{{ route('students.show', $enrollment->student) }}">
                                    {{ $enrollment->student->name }}
                                </a>
                            </td>
                            <td>
                                <a href="{{ route('subjects.show', $enrollment->subject) }}">
                                    {{ $enrollment->subject->name }}
                                </a>
                            </td>
                            <td>{{ $enrollment->enrollment_date->format('d/m/Y') }}</td>
                            <td>
                                @if($enrollment->status == 'enrolled')
                                    <span class="badge bg-success">Đã đăng ký</span>
                                @elseif($enrollment->status == 'completed')
                                    <span class="badge bg-info">Hoàn tất</span>
                                @else
                                    <span class="badge bg-danger">Rút khỏi</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('enrollments.edit', $enrollment) }}" class="btn btn-warning btn-sm">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('enrollments.destroy', $enrollment) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Bạn chắc chắn muốn xóa?')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">Không có đăng ký nào</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <nav>
            {{ $enrollments->links() }}
        </nav>
    </div>
</div>
@endsection
