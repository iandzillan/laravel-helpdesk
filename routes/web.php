<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\SubCategoryController;
use App\Http\Controllers\SubDepartmentController;
use App\Http\Controllers\UrgencyController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

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

// Route::get('/', function () {
//     return view('welcome');
// });

// Route::get  ('/dashboard', function(){
//     return view('layouts.app');
// });

Route::get  ('/', [LoginController::class, 'login'])->name('login');
Route::post ('/login-process', [LoginController::class, 'loginProcess'])->name('loginProcess');
Route::get  ('/logout', [LoginController::class, 'logout'])->name('logout');


Route::group(['middleware' => ['auth']], function(){
    Route::group(['middleware' => ['role:Admin']], function(){
        Route::get('/admin/dashboard', [DashboardController::class, 'adminHome'])->name('admin.dashboard');

        Route::get      ('/admin/categories',                   [CategoryController::class, 'index'])->name('admin.categories');
        Route::post     ('/admin/categories',                   [CategoryController::class, 'store'])->name('admin.categories.store');
        Route::get      ('/admin/categories/{category}/edit',   [CategoryController::class, 'show'])->name('admin.categories.show');
        Route::patch    ('/admin/categories/{category}',        [CategoryController::class, 'edit'])->name('admin.categories.update');
        Route::delete   ('/admin/categories/{category}',        [CategoryController::class, 'destroy'])->name('admin.categories.destroy');

        Route::get  ('/admin/sub-categories', [SubCategoryController::class, 'index'])->name('admin.subcategories');

        Route::get  ('/admin/urgencies', [UrgencyController::class, 'index'])->name('admin.urgencies');

        Route::get  ('/admin/users', [UserController::class, 'index'])->name('admin.users');

        Route::get  ('/admin/departments', [DepartmentController::class, 'index'])->name('admin.departments');

        Route::get  ('/admin/sub-departments', [SubDepartmentController::class, 'index'])->name('admin.subdepartments');
    });
});