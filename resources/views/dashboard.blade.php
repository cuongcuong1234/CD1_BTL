@extends('layouts.master')

@section('title', 'Bảng Điều Khiển')

@section('content')
<div class="container-fluid">
    <!-- Statistics Row -->
    <div class="row">
        <div class="col-md-3 col-sm-6 mb-4">
            <div class="stats-card">
                <div class="number">{{ $totalStudents }}</div>
                <div class="label">Tổng Sinh Viên</div>
                <small class="text-muted">
                    <i class="bi bi-arrow-up"></i> {{ $newStudentsThisMonth }} tháng này
                </small>
            </div>
        </div>

        <div class="col-md-3 col-sm-6 mb-4">
            <div class="stats-card">
                <div class="number">{{ $totalClassrooms }}</div>
                <div class="label">Tổng Lớp Học</div>
                <small class="text-muted">{{ $classroomMostStudents ? $classroomMostStudents->students_count . ' học sinh' : '' }}</small>
            </div>
        </div>

        <div class="col-md-3 col-sm-6 mb-4">
            <div class="stats-card">
                <div class="number">{{ $totalTeachers }}</div>
                <div class="label">Tổng Giáo Viên</div>
            </div>
        </div>

        <div class="col-md-3 col-sm-6 mb-4">
            <div class="stats-card">
                <div class="number">{{ $totalSubjects }}</div>
                <div class="label">Tổng Môn Học</div>
            </div>
        </div>
    </div>

    <!-- More Statistics -->
    <div class="row">
        <div class="col-md-3 col-sm-6 mb-4">
            <div class="stats-card">
                <div class="number">{{ $totalEnrollments }}</div>
                <div class="label">Tổng Đăng Ký</div>
            </div>
        </div>

        <div class="col-md-3 col-sm-6 mb-4">
            <div class="stats-card">
                <div class="number">{{ number_format($averageGrade ?? 0, 1) }}</div>
                <div class="label">Điểm Trung Bình</div>
            </div>
        </div>

        <div class="col-md-6">
            @if ($classroomMostStudents)
            <div class="card">
                <div class="card-header">
                    <i class="bi bi-door"></i> Lớp Có Học Sinh Nhiều Nhất
                </div>
                <div class="card-body">
                    <h5 class="card-title">{{ $classroomMostStudents->name }}</h5>
                    <p class="card-text">
                        <strong>Mã Lớp:</strong> {{ $classroomMostStudents->classroom_code }}<br>
                        <strong>Năm Học:</strong> {{ $classroomMostStudents->academic_year }}<br>
                        <strong>Sức Chứa:</strong> {{ $classroomMostStudents->students_count }}/{{ $classroomMostStudents->capacity }} 
                        <span class="badge 
                            @if ($classroomMostStudents->students_count >= $classroomMostStudents->capacity)
                                bg-danger
                            @elseif ($classroomMostStudents->students_count >= $classroomMostStudents->capacity * 0.8)
                                bg-warning
                            @else
                                bg-success
                            @endif
                        ">
                            @if ($classroomMostStudents->students_count >= $classroomMostStudents->capacity)
                                Đầy
                            @elseif ($classroomMostStudents->students_count >= $classroomMostStudents->capacity * 0.8)
                                Gần Đầy
                            @else
                                Còn Chỗ
                            @endif
                        </span>
                    </p>
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Recent Students -->
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <i class="bi bi-clock-history"></i> Sinh Viên Mới Nhất
                </div>
                <table class="table table-sm mb-0">
                    <thead>
                        <tr>
                            <th>Mã SV</th>
                            <th>Tên</th>
                            <th>Lớp</th>
                            <th>Ngày Tạo</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($recentStudents as $student)
                        <tr>
                            <td><small>{{ $student->student_code }}</small></td>
                            <td>{{ $student->name }}</td>
                            <td><small>{{ $student->classroom?->name ?? 'N/A' }}</small></td>
                            <td><small>{{ $student->created_at->format('d/m/Y') }}</small></td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted">Chưa có sinh viên</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Top Students -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <i class="bi bi-trophy"></i> Top 5 Sinh Viên Xuất Sắc
                </div>
                <table class="table table-sm mb-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Tên</th>
                            <th>Đăng Ký</th>
                            <th>Hành Động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($topStudents as $key => $student)
                        <tr>
                            <td>
                                <span class="badge bg-primary">{{ $key + 1 }}</span>
                            </td>
                            <td>{{ $student->name }}</td>
                            <td><small>{{ $student->enrollments_count }} môn</small></td>
                            <td>
                                <a href="{{ route('students.show', $student) }}" class="btn btn-sm btn-info">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted">Chưa có dữ liệu</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
