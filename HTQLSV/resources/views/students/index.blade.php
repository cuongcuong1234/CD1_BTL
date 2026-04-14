@extends('layouts.master')

@section('title', 'Danh Sách Sinh Viên')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Danh Sách Sinh Viên</h5>
        <a href="{{ route('students.create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-lg"></i> Thêm sinh viên
        </a>
    </div>
    <div class="card-body">
        <!-- Search and Filter -->
        <form method="GET" class="row g-3 mb-4">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control" placeholder="Tìm kiếm tên, email, mã..." 
                       value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <select name="classroom_id" class="form-select">
                    <option value="">-- Tất cả lớp --</option>
                    @foreach($classrooms as $classroom)
                        <option value="{{ $classroom->id }}" {{ request('classroom_id') == $classroom->id ? 'selected' : '' }}>
                            {{ $classroom->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <select name="status" class="form-select">
                    <option value="">-- Tất cả trạng thái --</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Hoạt động</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Không hoạt động</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-outline-primary w-100">
                    <i class="bi bi-search"></i> Tìm
                </button>
            </div>
        </form>

        <!-- Success Message -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Students Table -->
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Mã SV</th>
                        <th>Tên Sinh Viên</th>
                        <th>Email</th>
                        <th>Lớp</th>
                        <th>Điện Thoại</th>
                        <th>Trạng Thái</th>
                        <th>Thao Tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($students as $student)
                        <tr>
                            <td><strong>{{ $student->student_code }}</strong></td>
                            <td>{{ $student->name }}</td>
                            <td>{{ $student->email }}</td>
                            <td>{{ $student->classroom->name ?? 'N/A' }}</td>
                            <td>{{ $student->phone }}</td>
                            <td>
                                @if($student->status == 'active')
                                    <span class="badge bg-success">Hoạt động</span>
                                @else
                                    <span class="badge bg-secondary">Không hoạt động</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('students.show', $student) }}" class="btn btn-info btn-sm" title="Xem">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('students.edit', $student) }}" class="btn btn-warning btn-sm" title="Sửa">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('students.destroy', $student) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Bạn chắc chắn muốn xóa?')" title="Xóa">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">Không có sinh viên nào</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <nav>
            {{ $students->links() }}
        </nav>

        <!-- Trashed Students Link -->
        <div class="mt-3">
            <a href="{{ route('students.trashed') }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-recycle"></i> Xem sinh viên đã xóa
            </a>
        </div>
    </div>
</div>
@endsection
