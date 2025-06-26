<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TeacherController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Password Reset Routes
Route::post('/password/email', [AuthController::class, 'sendResetLinkEmail']);
Route::post('/password/reset', [AuthController::class, 'reset']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::apiResource('/students', StudentController::class);

    Route::get('/teacher/profile', [TeacherController::class, 'profile']);
    Route::put('teacher/profile', [TeacherController::class, 'update']);
    Route::post('/students/{student}/upload-picture', [StudentController::class, 'uploadProfilePicture']);
    Route::post('/teachers/upload-picture', [TeacherController::class, 'uploadProfilePicture']);
});
