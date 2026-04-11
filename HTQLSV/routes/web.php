<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\ClassroomController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\GradeController;
use App\Http\Controllers\EnrollmentController;

// Dashboard
Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

// Sinh viên
Route::resource('students', StudentController::class);
Route::get('students/restore/{id}', [StudentController::class, 'restore'])->name('students.restore');
Route::get('students-trashed', [StudentController::class, 'trashed'])->name('students.trashed');

// Lớp học
Route::resource('classrooms', ClassroomController::class);
Route::get('classrooms/restore/{id}', [ClassroomController::class, 'restore'])->name('classrooms.restore');

// Giáo viên
Route::resource('teachers', TeacherController::class);
Route::get('teachers/restore/{id}', [TeacherController::class, 'restore'])->name('teachers.restore');

// Môn học
Route::resource('subjects', SubjectController::class);
Route::get('subjects/restore/{id}', [SubjectController::class, 'restore'])->name('subjects.restore');

// Điểm
Route::resource('grades', GradeController::class);

// Đăng ký
Route::resource('enrollments', EnrollmentController::class);

