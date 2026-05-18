@extends('layouts.master')

@section('title', 'Danh Sách Lớp Học')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Danh Sách Lớp Học</h5>
        <a href="{{ route('classrooms.create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-lg"></i> Thêm lớp học
        </a>
    </div>
    <div class="card-body">
        <form method="GET" class="row g-3 mb-4">
            <div class="col-md-6">
                <input type="text" name="search" class="form-control" placeholder="Tìm kiếm tên lớp, mã lớp..." 
                       value="{{ request('search') }}">
            </div>
            <div class="col-md-4">
                <input type="text" name="academic_year" class="form-control" placeholder="Năm học (2025-2026)" 
                       value="{{ request('academic_year') }}">
            </div>
            <div class="col-md-2">
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
                        <th>Mã Lớp</th>
                        <th>Tên Lớp</th>
                        <th>Năm Học</th>
                        <th>Giáo Viên</th>
                        <th>Sức Chứa</th>
                        <th>Số SV</th>
                        <th>Thao Tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($classrooms as $classroom)
                        <tr>
                            <td><strong>{{ $classroom->classroom_code }}</strong></td>
                            <td>{{ $classroom->name }}</td>
                            <td>{{ $classroom->academic_year }}</td>
                            <td>{{ $classroom->teacher->name ?? 'N/A' }}</td>
                            <td>{{ $classroom->capacity }}</td>
                            <td>
                                <span class="badge bg-info">{{ $classroom->students_count ?? 0 }}</span>
                            </td>
                            <td>
                                <a href="{{ route('classrooms.show', $classroom) }}" class="btn btn-info btn-sm">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('classrooms.edit', $classroom) }}" class="btn btn-warning btn-sm">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('classrooms.destroy', $classroom) }}" method="POST" style="display:inline;">
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
                            <td colspan="7" class="text-center text-muted py-4">Không có lớp học nào</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <nav>
            {{ $classrooms->links() }}
        </nav>
    </div>
</div>
@endsection
