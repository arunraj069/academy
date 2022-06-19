<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\Teacher\TeacherLoginController;
use App\Http\Controllers\Teacher\TeacherDashboardController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('teacher/', [TeacherLoginController::class, 'showLogin'])->name('showLogin');
Route::group([
    'middleware' => ['assign.guard:teacher'],
    'as'    => 'teacher.',
], function () {
	Route::group([
	    'middleware' => ['guest:teacher'],
	    'as'    => 'teacher.',
	], function () {
		Route::get('/', [TeacherLoginController::class, 'showLogin'])->name('showLogin');
	});
	Route::group([
        'middleware' => ['throttle:60,1'],
    ], function () {
		Route::post('/', [TeacherLoginController::class, 'login'])->name('loginSubmit');	
	});
	Route::group([
        'middleware' => ['auth:teacher'],
    ], function () {
		Route::get('/home', [TeacherDashboardController::class, 'index'])->name('dashboard');
		Route::get('/list-student-marks', [TeacherDashboardController::class, 'getStudentMark'])->name('getStudentMark');
		Route::any('/list-student-details/', [AdminDashboardController::class, 'getStudentDetails'])->name('getStudentDetails');
		Route::get('/create-student-mark/{token?}',[TeacherDashboardController::class,'createStudentMark'])->name('createStudentMark');
		Route::post('/create-mark-submit',[TeacherDashboardController::class,'createStudentMarkSubmit'])->name('createStudentMarkSubmit');
		Route::delete('/delete-student-marks/{token?}',[TeacherDashboardController::class,'deleteStudentMark'])->name('deleteStudentMark');
	});
});
