<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ManagerController;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\SubCategoryController;
use App\Http\Controllers\SubDepartmentController;
use App\Http\Controllers\TicketController;
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
        Route::get('/admin/dashboard/category-chart', [DashboardController::class, 'getCategoryYear'])->name('admin.dashboard.category');

        Route::get('/admin/categories', [CategoryController::class, 'index'])->name('admin.categories');
        Route::post('/admin/categories', [CategoryController::class, 'store'])->name('admin.categories.store');
        Route::get('/admin/categories/{category}/edit', [CategoryController::class, 'show'])->name('admin.categories.show');
        Route::patch('/admin/categories/{category}', [CategoryController::class, 'update'])->name('admin.categories.update');
        Route::delete('/admin/categories/{category}', [CategoryController::class, 'destroy'])->name('admin.categories.destroy');

        Route::get('/admin/sub-categories', [SubCategoryController::class, 'index'])->name('admin.subcategories');
        Route::get('/admin/sub-categories/category', [SubCategoryController::class, 'getCategories'])->name('admin.subcategories.categories');
        Route::get('/admin/sub-categories/technician', [SubCategoryController::class, 'getTechnicians'])->name('admin.subcategories.technicians');
        Route::post('/admin/sub-categories', [SubCategoryController::class, 'store'])->name('admin.subcategories.store');
        Route::get('/admin/sub-categories/{subcategory}/edit', [SubCategoryController::class, 'show'])->name('admin.subcategories.show');
        Route::patch('/admin/sub-categories/{subcategory}', [SubCategoryController::class, 'update'])->name('admin.subcategories.update');
        Route::delete('/admin/sub-categories/{subcategory}', [SubCategoryController::class, 'destroy'])->name('admin.subcategories.destroy');

        Route::get('/admin/urgencies', [UrgencyController::class, 'index'])->name('admin.urgencies');
        Route::post('/admin/urgencies', [UrgencyController::class, 'store'])->name('admin.urgencies.store');
        Route::get('/admin/urgencies/{urgency}/edit', [UrgencyController::class, 'show'])->name('admin.urgencies.show');
        Route::patch('/admin/urgencies/{urgency}', [UrgencyController::class, 'update'])->name('admin.urgencies.update');
        Route::delete('/admin/urgencies/{urgency}', [UrgencyController::class, 'destroy'])->name('admin.urgencies.destroy');

        Route::get('/admin/departments', [DepartmentController::class, 'index'])->name('admin.departments');
        Route::post('/admin/departments', [DepartmentController::class, 'store'])->name('admin.departments.store');
        Route::get('/admin/departments/{department}/edit', [DepartmentController::class, 'show'])->name('admin.departments.show');
        Route::patch('/admin/departments/{department}', [DepartmentController::class, 'update'])->name('admin.departments.update');
        Route::delete('/admin/departments/{department}', [DepartmentController::class, 'destroy'])->name('admin.departments.destroy');

        Route::get('/admin/managers', [EmployeeController::class, 'index'])->name('admin.managers');
        Route::get('/admin/managers/dept', [EmployeeController::class, 'getDepts'])->name('admin.managers.depts');
        Route::get('/admin/managers/list', [EmployeeController::class, 'list'])->name('admin.managers.list');
        Route::post('/admin/managers', [EmployeeController::class, 'store'])->name('admin.managers.store');
        Route::get('/admin/managers/{manager}/edit', [EmployeeController::class, 'show'])->name('admin.managers.show');
        Route::patch('/admin/managers/{manager}', [EmployeeController::class, 'update'])->name('admin.managers.update');
        Route::delete('/admin/managers/{manager}', [EmployeeController::class, 'destroy'])->name('admin.managers.destroy');

        Route::get('/admin/users', [UserController::class, 'index'])->name('admin.users');
        Route::post('/admin/users', [UserController::class, 'store'])->name('admin.users.store');
        Route::get('/admin/users/{user}/edit', [UserController::class, 'show'])->name('admin.users.show');
        Route::patch('/admin/users/{user}', [UserController::class, 'update'])->name('admin.users.update');
        Route::delete('/admin/users/{user}', [UserController::class, 'destroy'])->name('admin.users.destroy');

        Route::post('/admin/users-request/', [UserController::class, 'request'])->name('admin.users.request');
        Route::get('/admin/users-request/get-employee', [UserController::class, 'getEmployees'])->name('admin.users.getEmployees');
        Route::get('/admin/users-request/get-employee/{nik}', [UserController::class, 'getEmployee'])->name('admin.users.getEmployee');
        Route::get('/admin/users-request/account-active', [UserController::class, 'accountActive'])->name('admin.users.accountActive');

        Route::get('/admin/all-tickets', [TicketController::class, 'allTicket'])->name('admin.all.tickets');
        Route::get('/admin/all-tickets/{ticket}', [TicketController::class, 'show'])->name('admin.all.tickets.show');

        Route::get('/admin/unassigned-tickets', [TicketController::class, 'newEntry'])->name('admin.entry.tickets');
        Route::get('/admin/unassigned-tickets/{ticket}', [TicketController::class, 'show'])->name('admin.entry.tickets.show');
        Route::get('/admin/unassigned-tickets/technician/{ticket}', [TicketController::class, 'getTechnicians'])->name('admin.entry.tickets.technicians');
        Route::get('/admin/unassigned-tickets/urgencies/{ticket}', [TicketController::class, 'getUrgencies'])->name('admin.entry.tickets.urgencies');
        Route::patch('/admin/unassigned-tickets/{ticket}/assign', [TicketController::class, 'assign'])->name('admin.entry.tickets.assign');
        Route::patch('/admin/unassigned-tickets/{ticket}/reject', [TicketController::class, 'reject'])->name('admin.entry.tickets.reject');

        Route::get('/admin/onwork-tickets', [TicketController::class, 'onWork'])->name('admin.tickets.onwork');
        Route::get('/admin/onwork-tickets/{ticket}', [TicketController::class, 'show'])->name('admin.tickets.onwork.show');

        Route::get('/admin/closed-tickets', [TicketController::class, 'closed'])->name('admin.tickets.closed');
        Route::get('/admin/closed-tickets/{ticket}', [TicketController::class, 'show'])->name('admin.tickets.closed.show');

        Route::get('/admin/rejected-tickets', [TicketController::class, 'rejected'])->name('admin.tickets.rejected');
        Route::get('/admin/rejected-tickets/{ticket}', [TicketController::class, 'show'])->name('admin.tickets.rejected.show');
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

        Route::get('/dept/create-ticket', [TicketController::class, 'createTicket'])->name('dept.create.ticket');
        Route::get('/dept/create-ticket/category', [TicketController::class, 'getCategory'])->name('dept.get.category');
        Route::get('/dept/create-ticket/sub-category/{category}', [TicketController::class, 'getSubCategory'])->name('dept.get.subCategory');
        Route::post('/dept/create-ticket', [TicketController::class, 'store'])->name('dept.ticket.store');

        Route::get('/dept/my-tickets', [TicketController::class, 'myTicket'])->name('dept.my.tickets');
        Route::get('/dept/my-tickets/{ticket}', [TicketController::class, 'show'])->name('dept.my.tickets.show');

        Route::get('/dept/all-tickets', [TicketController::class, 'allTicket'])->name('dept.all.tickets');
        Route::get('/dept/all-tickets/{ticket}', [TicketController::class, 'show'])->name('dept.all.tickets.show');

        Route::get('/dept/entry-tickets', [TicketController::class, 'newEntry'])->name('dept.entry.tickets');
        Route::get('/dept/entry-tickets/{ticket}', [TicketController::class, 'show'])->name('dept.entry.tickets.show');
        Route::patch('/dept/entry-tickets/{ticket}/approve', [TicketController::class, 'approve'])->name('dept.entry.tickets.approve');
        Route::patch('/dept/entry-tickets/{ticket}/reject', [TicketController::class, 'reject'])->name('dept.entry.tickets.reject');

        Route::get('/dept/onwork-tickets', [TicketController::class, 'onWork'])->name('dept.tickets.onwork');
        Route::get('/dept/onwork-tickets/{ticket}', [TicketController::class, 'show'])->name('dept.tickets.onwork.show');

        Route::get('/dept/closed-tickets', [TicketController::class, 'closed'])->name('dept.tickets.closed');
        Route::get('/dept/closed-tickets/{ticket}', [TicketController::class, 'show'])->name('dept.tickets.closed.show');

        Route::get('/dept/rejected-tickets', [TicketController::class, 'rejected'])->name('dept.tickets.rejected');
        Route::get('/dept/rejected-tickets/{ticket}', [TicketController::class, 'show'])->name('dept.tickets.rejected.show');
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

        Route::get('/subdept/create-ticket', [TicketController::class, 'createTicket'])->name('subdept.create.ticket');
        Route::get('/subdept/create-ticket/category', [TicketController::class, 'getCategory'])->name('subdept.get.category');
        Route::get('/subdept/create-ticket/sub-category/{category}', [TicketController::class, 'getSubCategory'])->name('subdept.get.subCategory');
        Route::post('/subdept/create-ticket', [TicketController::class, 'store'])->name('subdept.ticket.store');

        Route::get('/subdept/my-tickets', [TicketController::class, 'myTicket'])->name('subdept.my.tickets');
        Route::get('/subdept/my-tickets/{ticket}', [TicketController::class, 'show'])->name('subdept.my.tickets.show');

        Route::get('/subdept/all-tickets', [TicketController::class, 'allTicket'])->name('subdept.all.tickets');
        Route::get('/subdept/all-tickets/{ticket}', [TicketController::class, 'show'])->name('subdept.all.tickets.show');

        Route::get('/subdept/entry-tickets', [TicketController::class, 'newEntry'])->name('subdept.entry.tickets');
        Route::get('/subdept/entry-tickets/{ticket}', [TicketController::class, 'show'])->name('subdept.entry.tickets.show');
        Route::patch('/subdept/entry-tickets/{ticket}/approve', [TicketController::class, 'approve'])->name('subdept.entry.tickets.approve');
        Route::patch('/subdept/entry-tickets/{ticket}/reject', [TicketController::class, 'reject'])->name('subdept.entry.tickets.reject');

        Route::get('/subdept/onwork-tickets', [TicketController::class, 'onWork'])->name('subdept.tickets.onwork');
        Route::get('/subdept/onwork-tickets/{ticket}', [TicketController::class, 'show'])->name('subdept.tickets.onwork.show');

        Route::get('/subdept/closed-tickets', [TicketController::class, 'closed'])->name('subdept.tickets.closed');
        Route::get('/subdept/closed-tickets/{ticket}', [TicketController::class, 'show'])->name('subdept.tickets.closed.show');

        Route::get('/subdept/rejected-tickets', [TicketController::class, 'rejected'])->name('subdept.tickets.rejected');
        Route::get('/subdept/rejected-tickets/{ticket}', [TicketController::class, 'show'])->name('subdept.tickets.rejected.show');
    });

    Route::group(['middleware' => ['role:User']], function () {
        Route::get('/user/dashboard', [DashboardController::class, 'index'])->name('user.dashboard');

        Route::get('/user/create-ticket', [TicketController::class, 'createTicket'])->name('user.create.ticket');
        Route::get('/user/create-ticket/category', [TicketController::class, 'getCategory'])->name('user.get.category');
        Route::get('/user/create-ticket/sub-category/{category}', [TicketController::class, 'getSubCategory'])->name('user.get.subCategory');
        Route::post('/user/create-ticket', [TicketController::class, 'store'])->name('user.ticket.store');

        Route::get('/user/my-tickets', [TicketController::class, 'myTicket'])->name('user.my.tickets');
        Route::get('/user/my-tickets/{ticket}', [TicketController::class, 'show'])->name('user.my.tickets.show');

        Route::get('/user/onwork-tickets', [TicketController::class, 'onWork'])->name('user.tickets.onwork');
        Route::get('/user/onwork-tickets/{ticket}', [TicketController::class, 'show'])->name('user.tickets.onwork.show');

        Route::get('/user/closed-tickets', [TicketController::class, 'closed'])->name('user.tickets.closed');
        Route::get('/user/closed-tickets/{ticket}', [TicketController::class, 'show'])->name('user.tickets.closed.show');

        Route::get('/user/rejected-tickets', [TicketController::class, 'rejected'])->name('user.tickets.rejected');
        Route::get('/user/rejected-tickets/{ticket}', [TicketController::class, 'show'])->name('user.tickets.rejected.show');
    });

    Route::group(['middleware' => ['role:Technician']], function () {
        Route::get('/technician/dashboard', [DashboardController::class, 'index'])->name('technician.dashboard');

        Route::get('/technician/onwork-tickets', [TicketController::class, 'onWork'])->name('technician.tickets.onwork');
        Route::get('/technician/onwork-tickets/{ticket}', [TicketController::class, 'show'])->name('technician.tickets.onwork.show');
        Route::get('/technician/onwork-tickets/progress/{ticket}', [TicketController::class, 'getProgress'])->name('technician.tickets.onwork.progress');
        Route::patch('/technician/onwork-tickets/{ticket}', [TicketController::class, 'update'])->name('technician.tickets.onwork.update');
        Route::patch('/technician/onwork-tickets/pending/{ticket}', [TicketController::class, 'pending'])->name('technician.tickets.onwork.pending');
        Route::patch('/technician/onwork-tickets/continue/{ticket}', [TicketController::class, 'continue'])->name('technician.tickets.onwork.continue');

        Route::get('/technician/closed-tickets', [TicketController::class, 'closed'])->name('technician.tickets.closed');
        Route::get('/technician/closed-tickets/{ticket}', [TicketController::class, 'show'])->name('technician.tickets.closed.show');
    });
});
