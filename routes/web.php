<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\EmployeeController;
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

Route::get('/', [LoginController::class, 'login'])->name('login');
Route::post('/login-process', [LoginController::class, 'loginProcess'])->name('loginProcess');
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');


Route::group(['middleware' => ['auth']], function () {
    Route::group(['middleware' => ['role:Admin']], function () {
        Route::get('/admin/dashboard', [DashboardController::class, 'adminHome'])->name('admin.dashboard');

        Route::get('/admin/categories', [CategoryController::class, 'index'])->name('admin.categories');
        Route::post('/admin/categories', [CategoryController::class, 'store'])->name('admin.categories.store');
        Route::get('/admin/categories/{category}/edit', [CategoryController::class, 'show'])->name('admin.categories.show');
        Route::patch('/admin/categories/{category}', [CategoryController::class, 'update'])->name('admin.categories.update');
        Route::delete('/admin/categories/{category}', [CategoryController::class, 'destroy'])->name('admin.categories.destroy');

        Route::get('/admin/sub-categories', [SubCategoryController::class, 'index'])->name('admin.subcategories');
        Route::get('/admin/sub-categories/category', [SubCategoryController::class, 'getCategories'])->name('admin.subcategories.categories');
        Route::post('/admin/sub-categories', [SubCategoryController::class, 'store'])->name('admin.subcategories.store');
        Route::get('/admin/sub-categories/{subcategory}/edit', [SubCategoryController::class, 'show'])->name('admin.subcategories.show');
        Route::patch('/admin/sub-categories/{subcategory}', [SubCategoryController::class, 'update'])->name('admin.subcategories.update');
        Route::delete('/admin/sub-categories/{subcategory}', [SubCategoryController::class, 'destroy'])->name('admin.subcategories.destroy');

        Route::get('/admin/urgencies', [UrgencyController::class, 'index'])->name('admin.urgencies');
        Route::post('/admin/urgencies', [UrgencyController::class, 'store'])->name('admin.urgencies.store');
        Route::get('/admin/urgencies/{urgency}/edit', [UrgencyController::class, 'show'])->name('admin.urgencies.show');
        Route::patch('/admin/urgencies/{urgency}', [UrgencyController::class, 'update'])->name('admin.urgencies.update');
        Route::delete('/admin/urgencies/{urgency}', [UrgencyController::class, 'destroy'])->name('admin.urgencies.destroy');

        Route::get('/admin/users', [UserController::class, 'index'])->name('admin.users');
        Route::post('/admin/users', [UserController::class, 'store'])->name('admin.users.store');

        Route::get('/admin/departments', [DepartmentController::class, 'index'])->name('admin.departments');
        Route::post('/admin/departments', [DepartmentController::class, 'store'])->name('admin.departments.store');
        Route::get('/admin/departments/{department}/edit', [DepartmentController::class, 'show'])->name('admin.departments.show');
        Route::patch('/admin/departments/{department}', [DepartmentController::class, 'update'])->name('admin.departments.update');
        Route::delete('/admin/departments/{department}', [DepartmentController::class, 'destroy'])->name('admin.departments.destroy');

        Route::get('/admin/sub-departments', [SubDepartmentController::class, 'index'])->name('admin.subdepartments');
        Route::get('/admin/sub-departments/depts', [SubDepartmentController::class, 'getDepts'])->name('admin.subdepartments.getDepts');
        Route::post('/admin/sub-departments', [SubDepartmentController::class, 'store'])->name('admin.subdepartments.store');
        Route::get('/admin/sub-departments/{subdept}/edit', [SubDepartmentController::class, 'show'])->name('admin.subdepartments.show');
        Route::patch('/admin/sub-departments/{subdept}', [SubDepartmentController::class, 'update'])->name('admin.subdepartments.update');
        Route::delete('/admin/sub-departments/{subdept}', [SubDepartmentController::class, 'destroy'])->name('admin.subdepartments.destroy');
    });

    Route::group(['middleware' => ['role:Approver']], function () {
        Route::get('/approver/dashboard', [DashboardController::class, 'approverHome'])->name('approver.dashboard');

        Route::get('/approver/new-employee', [EmployeeController::class, 'index'])->name('approver.employees.index');
        Route::get('/approver/new-employee/dept', [EmployeeController::class, 'getDepts'])->name('approver.employees.depts');
        Route::get('/approver/new-employee/subdept', [EmployeeController::class, 'getSubDepts'])->name('approver.employees.subdepts');
        Route::get('/approver/new-employee/position', [EmployeeController::class, 'getPositions'])->name('approver.employees.positions');
        Route::post('/approver/new-employee', [EmployeeController::class, 'store'])->name('approver.employees.store');

        Route::get('/approver/employees', [EmployeeController::class, 'list'])->name('approver.employees.list');
        Route::get('/approver/employees/{employee}/edit', [EmployeeController::class, 'show'])->name('approver.employees.show');
        Route::put('/approver/employees/update', [EmployeeController::class, 'update'])->name('approver.employees.update');
        Route::delete('/approver/employees/{employee}', [EmployeeController::class, 'destroy'])->name('approver.employees.destroy');
    });
});
