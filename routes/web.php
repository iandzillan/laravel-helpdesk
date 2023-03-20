<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PositionController;
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
        Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

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
        Route::get('/admin/users/{user}/edit', [UserController::class, 'show'])->name('admin.users.show');
        Route::patch('/admin/users/{user}', [UserController::class, 'update'])->name('admin.users.update');
        Route::delete('/admin/users/{user}', [UserController::class, 'destroy'])->name('admin.users.destroy');


        Route::post('/admin/users-request/', [UserController::class, 'request'])->name('admin.users.request');
        Route::get('/admin/users-request/get-employee', [UserController::class, 'getEmployee'])->name('admin.users.getEmployee');
        Route::get('/admin/users-request/account-active', [UserController::class, 'accountActive'])->name('admin.users.accountActive');

        Route::get('/admin/new-employees', [EmployeeController::class, 'index'])->name('admin.employees.new');
        Route::get('/admin/new-employees/position', [EmployeeController::class, 'getPositions'])->name('admin.employees.positions');
        Route::post('/admin/employees', [EmployeeController::class, 'store'])->name('admin.employees.store');

        Route::get('/admin/employees', [EmployeeController::class, 'list'])->name('admin.employees.list');
        Route::get('/admin/employees/{employee}/edit', [EmployeeController::class, 'show'])->name('admin.employees.show');
        Route::patch('/admin/employees/{employee}', [EmployeeController::class, 'update'])->name('admin.employees.update');
        Route::delete('/admin/employees/{employee}', [EmployeeController::class, 'destroy'])->name('admin.employees.destroy');

        Route::get('/admin/departments', [DepartmentController::class, 'index'])->name('admin.departments');
        Route::post('/admin/departments', [DepartmentController::class, 'store'])->name('admin.departments.store');
        Route::get('/admin/departments/{department}/edit', [DepartmentController::class, 'show'])->name('admin.departments.show');
        Route::patch('/admin/departments/{department}', [DepartmentController::class, 'update'])->name('admin.departments.update');
        Route::delete('/admin/departments/{department}', [DepartmentController::class, 'destroy'])->name('admin.departments.destroy');
    });

    Route::group(['middleware' => ['role:Approver1']], function () {
        Route::get('/dept/dashboard', [DashboardController::class, 'index'])->name('dept.dashboard');

        Route::get('/dept/sub-departments', [SubDepartmentController::class, 'index'])->name('dept.subdepartments');
        Route::post('/dept/sub-departments', [SubDepartmentController::class, 'store'])->name('dept.subdepartments.store');
        Route::get('/dept/sub-departments/{subdept}/edit', [SubDepartmentController::class, 'show'])->name('dept.subdepartments.show');
        Route::patch('/dept/sub-departments/{subdept}', [SubDepartmentController::class, 'update'])->name('dept.subdepartments.update');
        Route::delete('/dept/sub-departments/{subdept}', [SubDepartmentController::class, 'destroy'])->name('dept.subdepartments.destroy');

        Route::get('/dept/new-employees', [EmployeeController::class, 'index'])->name('dept.employees.new');
        Route::get('/dept/new-employees/subdept', [EmployeeController::class, 'getSubdepts'])->name('dept.employees.subdepts');
        Route::get('/dept/new-employees/position', [EmployeeController::class, 'getPositions'])->name('dept.employees.positions');
        Route::post('/dept/employees', [EmployeeController::class, 'store'])->name('dept.employees.store');

        Route::get('/dept/employees', [EmployeeController::class, 'list'])->name('dept.employees.list');
        Route::get('/dept/employees/{employee}/edit', [EmployeeController::class, 'show'])->name('dept.employees.show');
        Route::patch('/dept/employees/{employee}', [EmployeeController::class, 'update'])->name('dept.employees.update');
        Route::delete('/dept/employees/{employee}', [EmployeeController::class, 'destroy'])->name('dept.employees.destroy');

        Route::get('/dept/user-request', [EmployeeController::class, 'userRequestList'])->name('dept.userrequestlist');
        Route::get('/dept/user-request/{employee}', [EmployeeController::class, 'userRequest'])->name('dept.userrequest');
        Route::patch('/dept/is-request', [EmployeeController::class, 'isRequest'])->name('dept.isRequest');
        Route::get('/dept/send-request', [EmployeeController::class, 'sendRequest'])->name('dept.sendRequest');

        Route::get('/dept/positions', [PositionController::class, 'index'])->name('dept.positions');
        Route::get('/dept/positions/getSubdept', [PositionController::class, 'getSubdept'])->name('dept.positions.getSubdept');
        Route::post('/dept/positions', [PositionController::class, 'store'])->name('dept.positions.store');
        Route::get('/dept/positions/{position}/edit', [PositionController::class, 'show'])->name('dept.positions.show');
        Route::patch('/dept/positions/{position}', [PositionController::class, 'update'])->name('dept.positions.update');
        Route::delete('/dept/positions/{position}', [PositionController::class, 'destroy'])->name('dept.positions.destroy');
    });

    Route::group(['middleware' => ['role:Approver2']], function () {
        Route::get('/subdept/dashboard', [DashboardController::class, 'index'])->name('subdept.dashboard');

        Route::get('/subdept/new-employee', [EmployeeController::class, 'index'])->name('subdept.employees.index');
        Route::get('/subdept/new-employee/position', [EmployeeController::class, 'getPositions'])->name('subdept.employees.positions');
        Route::post('/subdept/new-employee', [EmployeeController::class, 'store'])->name('subdept.employees.store');

        Route::get('/subdept/employees', [EmployeeController::class, 'list'])->name('subdept.employees.list');
        Route::get('/subdept/employees/{employee}/edit', [EmployeeController::class, 'show'])->name('subdept.employees.show');
        Route::patch('/subdept/employees/{employee}', [EmployeeController::class, 'update'])->name('subdept.employees.update');
        Route::delete('/subdept/employees/{employee}', [EmployeeController::class, 'destroy'])->name('subdept.employees.destroy');

        Route::get('/subdept/user-request', [EmployeeController::class, 'userRequestList'])->name('subdept.userrequestlist');
        Route::get('/subdept/user-request/{employee}', [EmployeeController::class, 'userRequest'])->name('subdept.userrequest');
        Route::patch('/subdept/is-request', [EmployeeController::class, 'isRequest'])->name('subdept.isRequest');
        Route::get('/subdept/send-request', [EmployeeController::class, 'sendRequest'])->name('subdept.sendRequest');

        Route::get('/subdept/positions', [PositionController::class, 'index'])->name('subdept.positions');
        Route::post('/subdept/positions', [PositionController::class, 'store'])->name('subdept.positions.store');
        Route::get('/subdept/positions/{position}/edit', [PositionController::class, 'show'])->name('subdept.positions.show');
        Route::patch('/subdept/positions/{position}', [PositionController::class, 'update'])->name('subdept.positions.update');
        Route::delete('/subdept/positions/{position}', [PositionController::class, 'destroy'])->name('subdept.positions.destroy');
    });
});
