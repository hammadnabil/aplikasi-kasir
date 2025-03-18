<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\LogController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\WaiterOrderController;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard')->middleware('auth');



Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);


Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin/users', [UserController::class, 'index'])->name('admin.users.index');
    Route::get('/admin/users/create', [UserController::class, 'create'])->name('admin.users.create');
    Route::post('/admin/users', [UserController::class, 'store'])->name('admin.users.store');
    Route::get('/admin/users/{user}/edit', [UserController::class, 'edit'])->name('admin.users.edit');
    Route::put('/admin/users/{user}', [UserController::class, 'update'])->name('admin.users.update');
    Route::delete('/admin/users/{user}', [UserController::class, 'destroy'])->name('admin.users.destroy');
    Route::get('/admin/logs', [LogController::class, 'index'])->name('admin.logs.index');
});

Route::middleware(['auth', 'manager'])->prefix('manager')->group(function () {
    Route::get('menu/create', [MenuController::class, 'create'])->name('manager.menu.create'); 
    Route::post('menu', [MenuController::class, 'store'])->name('menu.store');
    Route::get('menu/{menu}/edit', [MenuController::class, 'edit'])->name('manager.menu.edit');
    Route::put('menu/{menu}', [MenuController::class, 'update'])->name('manager.menu.update');
    Route::delete('menu/{menu}', [MenuController::class, 'destroy'])->name('manager.menu.destroy');
    Route::get('menu', [MenuController::class, 'index'])->name('manager.menu.index');
    Route::get('transactions', [TransactionController::class, 'index'])->name('manager.transactions.index');
    Route::get('reports', [ReportController::class, 'index'])->name('manager.reports.index');
    Route::get('logs', [LogController::class, 'index'])->name('manager.logs.index');
});

Route::get('orders/create', [OrderController::class, 'create'])->name('waiter.order.create');








Route::get('/waiter/orders', [WaiterOrderController::class, 'index'])->name('waiter.orders.index');
Route::get('/waiter/orders/create', [WaiterOrderController::class, 'create'])->name('waiter.orders.create');
Route::post('/waiter/orders/store', [WaiterOrderController::class, 'store'])->name('waiter.orders.store');
Route::get('/waiter/orders/{id}/edit', [WaiterOrderController::class, 'edit'])->name('waiter.orders.edit');
Route::put('/waiter/orders/{id}', [WaiterOrderController::class, 'update'])->name('waiter.orders.update');






Route::get('/menus/search', [MenuController::class, 'search'])->name('menus.search');








Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
