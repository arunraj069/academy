<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\Admin\AdminLoginController;
use App\Http\Controllers\Admin\AdminDashboardController;
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
Route::group([
    'middleware' => ['assign.guard:admin'],
    'as'    => 'admin.',
], function () {
	Route::group([
	    'middleware' => ['guest:admin'],
	    'as'    => 'admin.',
	], function () {
		Route::get('/', [AdminLoginController::class, 'showLogin'])->name('showLogin');
	});
	Route::group([
        'middleware' => ['throttle:60,1'],
    ], function () {
		Route::post('/', [AdminLoginController::class, 'login'])->name('loginSubmit');	
	});
	Route::group([
        'middleware' => ['auth:admin'],
    ], function () {
		Route::get('/home', [AdminDashboardController::class, 'index'])->name('dashboard');
		Route::get('/list-student', [AdminDashboardController::class, 'getStudent'])->name('getStudent');
		Route::any('/list-student-details/', [AdminDashboardController::class, 'getStudentDetails'])->name('getStudentDetails');
		Route::get('/create-student/{token?}',[AdminDashboardController::class,'createStudent'])->name('createStudent');
		Route::post('/create-student',[AdminDashboardController::class,'createStudentSubmit'])->name('createStudentSubmit');
		Route::delete('/delete-student/{token?}',[AdminDashboardController::class,'deleteStudent'])->name('deleteStudent');
		Route::get('/get-term', [AdminDashboardController::class, 'getTerm'])->name('getTerm');
		Route::get('/create-term',[AdminDashboardController::class,'createTerm'])->name('createTerm');
		Route::post('/create-term',[AdminDashboardController::class,'createTermSubmit'])->name('createTermSubmit');
		Route::any('/list-term-details/', [AdminDashboardController::class, 'getTermDetails'])->name('getTermDetails');	
	});
});
