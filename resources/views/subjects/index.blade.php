@extends('layouts.master')

@section('title', 'Danh Sách Môn Học')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Danh Sách Môn Học</h5>
        <a href="{{ route('subjects.create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-lg"></i> Thêm môn học
        </a>
    </div>
    <div class="card-body">
        <form method="GET" class="row g-3 mb-4">
            <div class="col-md-6">
                <input type="text" name="search" class="form-control" placeholder="Tìm kiếm tên môn, mã môn..." 
                       value="{{ request('search') }}">
            </div>
            <div class="col-md-4">
                <select name="credits" class="form-select">
                    <option value="">-- Tất cả tín chỉ --</option>
                    @for($i = 1; $i <= 6; $i++)
                        <option value="{{ $i }}" {{ request('credits') == $i ? 'selected' : '' }}>{{ $i }} tín chỉ</option>
                    @endfor
                </select>
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
                        <th>Mã Môn</th>
                        <th>Tên Môn Học</th>
                        <th>Mô Tả</th>
                        <th>Tín Chỉ</th>
                        <th>Học Sinh</th>
                        <th>Thao Tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($subjects as $subject)
                        <tr>
                            <td><strong>{{ $subject->subject_code }}</strong></td>
                            <td>{{ $subject->name }}</td>
                            <td>{{ Str::limit($subject->description, 50) }}</td>
                            <td>
                                <span class="badge bg-secondary">{{ $subject->credits }}</span>
                            </td>
                            <td>
                                <span class="badge bg-info">{{ $subject->enrollments_count ?? 0 }}</span>
                            </td>
                            <td>
                                <a href="{{ route('subjects.show', $subject) }}" class="btn btn-info btn-sm">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('subjects.edit', $subject) }}" class="btn btn-warning btn-sm">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('subjects.destroy', $subject) }}" method="POST" style="display:inline;">
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
                            <td colspan="6" class="text-center text-muted py-4">Không có môn học nào</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <nav>
            {{ $subjects->links() }}
        </nav>
    </div>
</div>
@endsection
