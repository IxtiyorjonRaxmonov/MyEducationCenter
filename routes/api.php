<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Group_Subject_LevelsController;
use App\Http\Controllers\GroupDateController;
use App\Http\Controllers\GroupsController;
use App\Http\Controllers\LevelsController;
use App\Http\Controllers\RatesController;
use App\Http\Controllers\RolesController;
use App\Http\Controllers\StudentsController;
use App\Http\Controllers\SubjectsController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\UserGroupsController;
use App\Http\Controllers\UsersController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/



Route::post('login', [AdminController::class, 'login']);

Route::middleware(['auth:sanctum'])->group(function () {
                    // Admin
    Route::post('register', [AdminController::class, 'register']);
    Route::post('user_for_admin', [AdminController::class, 'userForAdmin']);
    Route::post('subject_for_admin', [AdminController::class, 'subjectForAdmin']);
    Route::post('subject_teacher_for_admin', [AdminController::class, 'subjectTeacherForAdmin']);
    Route::post('rate_for_admin', [AdminController::class, 'rateForAdmin']);
    Route::post('group_for_admin', [AdminController::class, 'groupForAdmin']);
    Route::post('role_for_admin', [AdminController::class, 'roleForAdmin']);
    Route::post('level_for_admin', [AdminController::class, 'levelForAdmin']);
    Route::post('user_group_for_admin', [AdminController::class, 'userGroupForAdmin']);
    Route::post('get_lowest_rating', [AdminController::class, 'theLowestRating']); 
    Route::post('attendance_students', [AdminController::class, 'attendanceStudents']); 
                    //Teacher
    Route::post('get_teacher_groups', [TeacherController::class, 'getTeacherGroups']); 
    Route::post('get_teacher_students', [TeacherController::class, 'getTeacherStudents']); 
    Route::post('rate_for_teacher', [TeacherController::class, 'rateForTeacher']); 
                    //Student
    Route::post('get_students_group', [StudentsController::class, 'getStudentsGroup']); 
    Route::post('get_students_group_date', [StudentsController::class, 'getStudentsGroupDate']); 
    Route::post('get_students_rating', [StudentsController::class, 'getStudentsRate']); 
    
    Route::resource('group', GroupsController::class);
    Route::resource('user_group', UserGroupsController::class);
    Route::resource('user', UsersController::class);
    Route::resource('level', LevelsController::class);
    Route::resource('role', RolesController::class);
    Route::resource('rate', RatesController::class);
    Route::resource('subject', SubjectsController::class);
    Route::resource('group_date', GroupDateController::class);
});
