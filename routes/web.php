<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\RolesController;
use App\Http\Controllers\MenuElementController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\ClusterController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\PasswordController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\PaymentController;

Auth::routes();
Route::view('/', 'welcome');

Route::group(['middleware' => ['get.menu', 'auth']], function () {
  Route::view('home',  'dashboard.homepage');

  Route::group(['middleware' => ['role:operator']], function () {
    Route::resource('clusters', ClusterController::class);
    Route::resource('customers', CustomerController::class)->except(['index', 'show']);
    Route::resource('payments', PaymentController::class);
  });
  
  Route::group(['middleware' => ['role:supervisor']], function () {
    Route::post('transactions/{transaction}/approve', [TransactionController::class, 'approve'])->name('transactions.approve');
  });
  
  Route::resource('customers', CustomerController::class)->only(['index', 'show']);

  Route::get('transactions/report', [TransactionController::class, 'report'])->name('transactions.report');
  Route::get('transactions/report/print', [TransactionController::class, 'printReport'])->name('transactions.report.print');
  Route::get('transactions/{transaction}/print', [TransactionController::class, 'print'])->name('transactions.print');
  Route::resource('transactions', TransactionController::class);

  Route::get('units/{unit}/debt', [UnitController::class, 'debt'])->name('units.debt');
  Route::post('units/sync', [UnitController::class, 'sync'])->name('units.sync');
  Route::get('units/export/{type}', [UnitController::class, 'export'])->name('units.export');
  Route::resource('units', UnitController::class);

  Route::get('change-password', [PasswordController::class, 'edit'])->name('passwords.edit');
  Route::post('change-password', [PasswordController::class, 'update'])->name('passwords.update');





// coreui

  Route::group(['middleware' => ['role:master']], function () {
    Route::resource('users',            UsersController::class)->except(['create', 'store']);
    Route::resource('roles',            RolesController::class);
    Route::get('/roles/move/move-up',   [RolesController::class, 'moveUp'])->name('roles.up');
    Route::get('/roles/move/move-down', [RolesController::class, 'available'])->name('roles.down');

    Route::prefix('menu/element')->group(function () {
      Route::get('/',                 [MenuElementController::class, 'index'])->name('menu.index');
      Route::get('/move-up',          [MenuElementController::class, 'moveUp'])->name('menu.up');
      Route::get('/move-down',        [MenuElementController::class, 'moveDown'])->name('menu.down');
      Route::get('/create',           [MenuElementController::class, 'create'])->name('menu.create');
      Route::post('/store',           [MenuElementController::class, 'store'])->name('menu.store');
      Route::get('/get-parents',      [MenuElementController::class, 'getParents']);
      Route::get('/edit',             [MenuElementController::class, 'edit'])->name('menu.edit');
      Route::post('/update',          [MenuElementController::class, 'update'])->name('menu.update');
      Route::get('/show',             [MenuElementController::class, 'show'])->name('menu.show');
      Route::get('/delete',           [MenuElementController::class, 'delete'])->name('menu.delete');
    });

    Route::prefix('menu/menu')->group(function () {
      Route::get('/',                 [MenuController::class, 'index'])->name('menu.menu.index');
      Route::get('/create',           [MenuController::class, 'create'])->name('menu.menu.create');
      Route::post('/store',           [MenuController::class, 'store'])->name('menu.menu.store');
      Route::get('/edit',             [MenuController::class, 'edit'])->name('menu.menu.edit');
      Route::post('/update',          [MenuController::class, 'update'])->name('menu.menu.update');
      Route::get('/delete',           [MenuController::class, 'delete'])->name('menu.menu.delete');
    });
  });
});
