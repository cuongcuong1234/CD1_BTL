@extends('layouts.master')

@section('title', 'Danh Sách Giáo Viên')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Danh Sách Giáo Viên</h5>
        <a href="{{ route('teachers.create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-lg"></i> Thêm giáo viên
        </a>
    </div>
    <div class="card-body">
        <form method="GET" class="row g-3 mb-4">
            <div class="col-md-5">
                <input type="text" name="search" class="form-control" placeholder="Tìm kiếm tên, email, mã..." 
                       value="{{ request('search') }}">
            </div>
            <div class="col-md-4">
                <select name="status" class="form-select">
                    <option value="">-- Tất cả trạng thái --</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Hoạt động</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Không hoạt động</option>
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
                        <th>Mã GV</th>
                        <th>Tên Giáo Viên</th>
                        <th>Email</th>
                        <th>Chuyên Môn</th>
                        <th>Điện Thoại</th>
                        <th>Trạng Thái</th>
                        <th>Thao Tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($teachers as $teacher)
                        <tr>
                            <td><strong>{{ $teacher->teacher_code }}</strong></td>
                            <td>{{ $teacher->name }}</td>
                            <td>{{ $teacher->email }}</td>
                            <td>{{ $teacher->specialization }}</td>
                            <td>{{ $teacher->phone }}</td>
                            <td>
                                @if($teacher->status == 'active')
                                    <span class="badge bg-success">Hoạt động</span>
                                @else
                                    <span class="badge bg-secondary">Không hoạt động</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('teachers.show', $teacher) }}" class="btn btn-info btn-sm">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('teachers.edit', $teacher) }}" class="btn btn-warning btn-sm">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('teachers.destroy', $teacher) }}" method="POST" style="display:inline;">
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
                            <td colspan="7" class="text-center text-muted py-4">Không có giáo viên nào</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <nav>
            {{ $teachers->links() }}
        </nav>
    </div>
</div>
@endsection
