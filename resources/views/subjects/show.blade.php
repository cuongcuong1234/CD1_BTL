@extends('layouts.master')

@section('title', 'Chi Tiết Môn Học')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card mb-3">
            <div class="card-header">
                <h5 class="mb-0">Thông Tin Môn Học</h5>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Mã Môn:</strong> {{ $subject->subject_code }}
                    </div>
                    <div class="col-md-6">
                        <strong>Tín Chỉ:</strong> {{ $subject->credits }}
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-12">
                        <strong>Tên Môn:</strong> {{ $subject->name }}
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-12">
                        <strong>Mô Tả:</strong>
                        <p>{{ $subject->description ?? 'N/A' }}</p>
                    </div>
                </div>

                <hr>

                <div class="d-flex gap-2">
                    <a href="{{ route('subjects.edit', $subject) }}" class="btn btn-warning">
                        <i class="bi bi-pencil"></i> Sửa
                    </a>
                    <form action="{{ route('subjects.destroy', $subject) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" onclick="return confirm('Bạn chắc chắn muốn xóa?')">
                            <i class="bi bi-trash"></i> Xóa
                        </button>
                    </form>
                    <a href="{{ route('subjects.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Quay Lại
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card mb-3">
            <div class="card-header">
                <h6 class="mb-0">Học Sinh Đăng Ký ({{ $subject->enrollments->count() }})</h6>
            </div>
            <div class="card-body" style="max-height: 300px; overflow-y: auto;">
                @if($subject->enrollments->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($subject->enrollments as $enrollment)
                            <a href="{{ route('students.show', $enrollment->student) }}" class="list-group-item list-group-item-action">
                                {{ $enrollment->student->name }}
                                <small class="text-muted d-block">{{ $enrollment->student->student_code }}</small>
                            </a>
                        @endforeach
                    </div>
                @else
                    <p class="text-muted">Chưa có học sinh đăng ký</p>
                @endif
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">Điểm Số</h6>
            </div>
            <div class="card-body" style="max-height: 300px; overflow-y: auto;">
                @if($subject->grades->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Học Sinh</th>
                                    <th>Điểm</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($subject->grades as $grade)
                                    <tr>
                                        <td>{{ Str::limit($grade->student->name, 15) }}</td>
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
    </div>
</div>
@endsection
