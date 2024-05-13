<?php

use App\Http\Controllers\Admin\InquiryController;
use App\Http\Controllers\Admin\StatusController;
use App\Http\Controllers\Admin\TreatmentController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\PermissionController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {

    Route::get('/', [App\Http\Controllers\Admin\DashboardController::class, 'index']);
    Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');

    Route::resources([
        'roles' => RoleController::class,
        'users' => UserController::class,
        'permissions' => PermissionController::class,
        'treatments' => TreatmentController::class,
        'status' => StatusController::class,
        'inquiries' => InquiryController::class,
    ]);

    Route::get('/inquiries/waiting', [InquiryController::class, 'waiting'])->name('inquiries.waiting');
    Route::get('/inquiries/approved', [InquiryController::class, 'approved'])->name('inquiries.approved');
});
