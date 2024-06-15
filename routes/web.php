<?php

use App\Http\Controllers\Admin\InventoryController;
use App\Http\Controllers\Admin\ProductCategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\RepoController;
use App\Models\ProductCategory;
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

Auth::routes();

Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {

    Route::get('/', [App\Http\Controllers\Admin\DashboardController::class, 'index']);
    Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');

    Route::get('inventory', [InventoryController::class, 'index'])->name('inventory.index');
    Route::post('inventory/search', [InventoryController::class, 'search'])->name('inventory.search');
    Route::post('inventory', [InventoryController::class, 'store'])->name('inventory.store');

    Route::resources([
        'roles' => RoleController::class,
        'users' => UserController::class,
        'permissions' => PermissionController::class,
        'repos' => RepoController::class,
        'product-categories' => ProductCategoryController::class,
        'products' => ProductController::class,
    ]);
});
