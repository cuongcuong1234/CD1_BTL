@extends('layouts.master')

@section('title', 'Danh Sách Điểm')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Danh Sách Điểm</h5>
        <a href="{{ route('grades.create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-lg"></i> Thêm điểm
        </a>
    </div>
    <div class="card-body">
        <form method="GET" class="row g-3 mb-4">
            <div class="col-md-4">
                <select name="student_id" class="form-select">
                    <option value="">-- Tất cả sinh viên --</option>
                    @foreach($students as $student)
                        <option value="{{ $student->id }}" {{ request('student_id') == $student->id ? 'selected' : '' }}>
                            {{ $student->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <select name="subject_id" class="form-select">
                    <option value="">-- Tất cả môn --</option>
                    @foreach($subjects as $subject)
                        <option value="{{ $subject->id }}" {{ request('subject_id') == $subject->id ? 'selected' : '' }}>
                            {{ $subject->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
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
                        <th>Điểm</th>
                        <th>Hạng</th>
                        <th>Thao Tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($grades as $grade)
                        <tr>
                            <td>{{ $grade->student->name }}</td>
                            <td>{{ $grade->subject->name }}</td>
                            <td>{{ $grade->score }}/10</td>
                            <td>
                                <span class="badge bg-info">{{ $grade->grade_letter }}</span>
                            </td>
                            <td>
                                <a href="{{ route('grades.edit', $grade) }}" class="btn btn-warning btn-sm">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('grades.destroy', $grade) }}" method="POST" style="display:inline;">
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
                            <td colspan="5" class="text-center text-muted py-4">Không có điểm nào</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <nav>
            {{ $grades->links() }}
        </nav>
    </div>
</div>
@endsection
