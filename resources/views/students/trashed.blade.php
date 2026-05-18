@extends('layouts.master')

@section('title', 'Sinh Viên Đã Xóa')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Sinh Viên Đã Xóa</h5>
        <a href="{{ route('students.index') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-arrow-left"></i> Quay Lại
        </a>
    </div>
    <div class="card-body">
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
                        <th>Mã SV</th>
                        <th>Tên Sinh Viên</th>
                        <th>Email</th>
                        <th>Lớp</th>
                        <th>Điện Thoại</th>
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
                                <form action="{{ route('students.restore', $student->id) }}" method="GET" style="display:inline;">
                                    <button type="submit" class="btn btn-success btn-sm" title="Khôi phục">
                                        <i class="bi bi-arrow-counterclockwise"></i> Khôi phục
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">Không có sinh viên đã xóa nào</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <nav>
            {{ $students->links() }}
        </nav>
    </div>
</div>
@endsection
