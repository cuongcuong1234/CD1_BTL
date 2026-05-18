<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\StudentApiController;
use App\Http\Controllers\Api\GradeApiController;

Route::middleware('api')->prefix('api/v1')->group(function () {
    // Student API endpoints
    Route::apiResource('students', StudentApiController::class);

    // Grade API endpoints
    Route::apiResource('grades', GradeApiController::class);
    Route::get('grades/statistics', [GradeApiController::class, 'statistics']);
    Route::get('students/{studentId}/average-grade', [GradeApiController::class, 'averageGrade']);
});
