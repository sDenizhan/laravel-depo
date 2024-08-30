<?php

use App\Http\Controllers\Admin\DoctorController;
use App\Http\Controllers\Admin\InventoryController;
use App\Http\Controllers\Admin\MyReposController;
use App\Http\Controllers\Admin\PrescriptionController;
use App\Http\Controllers\Admin\ProductCategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\RepoController;
use App\Http\Controllers\Admin\RequestsController;
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
    return redirect()->route('admin.dashboard');
});

Route::get('/setup', function (){
    Artisan::call('key:generate');
    Artisan::call('migrate:fresh --seed');
    Artisan::call('storage:link');
    return 'Setup completed';
});

Auth::routes();

Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {

    Route::get('/', [App\Http\Controllers\Admin\DashboardController::class, 'index']);
    Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');

    Route::get('inventory', [InventoryController::class, 'index'])->name('inventory.index');
    Route::post('inventory/search', [InventoryController::class, 'search'])->name('inventory.search');
    Route::post('inventory/store', [InventoryController::class, 'store'])->name('inventory.store');
    Route::post('inventory/check', [InventoryController::class, 'check'])->name('inventory.source-repo-check');
    Route::get('inventory/remove', [InventoryController::class, 'remove'])->name('inventory.remove');
    Route::post('inventory/remove', [InventoryController::class, 'remove_store'])->name('inventory.remove-store');

    //notify readed
    Route::post('notify/readed', [\App\Http\Controllers\Admin\DashboardController::class, 'notifyReaded'])->name('notifications.read');



    Route::get('my-repo', [MyReposController::class, 'index'])->name('my-repo.index');

    Route::get('my-repo/my-requests', [MyReposController::class, 'myRequests'])->name('my-repo.my-requests');
    Route::get('my-repo/new-request', [MyReposController::class, 'newRequest'])->name('my-repo.new-request');
    Route::post('my-repo/new-request', [MyReposController::class, 'storeRequest'])->name('my-repo.store-request');
    Route::get('my-repo/request/{id}', [MyReposController::class, 'showRequest'])->name('my-repo.show-request');

    //request
    Route::post('requests/check', [RequestsController::class, 'check'])->name('requests.check-product-quantity');
    Route::post('requests/statusUpdate', [RequestsController::class, 'statusUpdate'])->name('requests.status-update');

    //prescription search
    Route::post('prescriptions/search', [PrescriptionController::class, 'search'])->name('prescriptions.search');
    Route::post('prescriptions/select', [PrescriptionController::class, 'select'])->name('prescriptions.select-medicine');
    Route::get('prescriptions/print/{id}', [PrescriptionController::class, 'print'])->name('prescriptions.print');


    //alert
    Route::get('alert-settings', [App\Http\Controllers\Admin\AlertSettingsController::class, 'index'])->name('alert-settings.index');
    Route::post('alert-settings', [App\Http\Controllers\Admin\AlertSettingsController::class, 'store'])->name('alert-settings.store');

    //email
    Route::get('email-settings', [App\Http\Controllers\Admin\EmailSettingsController::class, 'index'])->name('email-settings.index');
    Route::post('email-settings', [App\Http\Controllers\Admin\EmailSettingsController::class, 'store'])->name('email-settings.store');


    Route::resources([
        'roles' => RoleController::class,
        'users' => UserController::class,
        'permissions' => PermissionController::class,
        'repos' => RepoController::class,
        'product-categories' => ProductCategoryController::class,
        'products' => ProductController::class,
        'requests' => RequestsController::class,
        'prescriptions' => PrescriptionController::class,
        'doctors' => DoctorController::class,
    ]);
});
