<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\RolesController;
use App\Http\Controllers\MailController;
use App\Http\Controllers\MenuElementController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\MediaController;

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

Auth::routes();

Route::group(['middleware' => ['get.menu']], function () {
    Route::group(['middleware' => ['auth']], function () {
        Route::view('/',                  'dashboard.homepage');
    });

    Route::group(['middleware' => ['role:user']], function () {
        Route::view('/colors',            'dashboard.colors');
        Route::view('/typography',        'dashboard.typography');
        Route::view('/charts',            'dashboard.charts');
        Route::view('/widgets',           'dashboard.widgets');
        Route::view('/404',               'dashboard.404');
        Route::view('/500',               'dashboard.500');
        Route::prefix('base')->group(function () {
            Route::view('/breadcrumb',    'dashboard.base.breadcrumb');
            Route::view('/cards',         'dashboard.base.cards');
            Route::view('/carousel',      'dashboard.base.carousel');
            Route::view('/collapse',      'dashboard.base.collapse');

            Route::view('/forms',         'dashboard.base.forms');
            Route::view('/jumbotron',     'dashboard.base.jumbotron');
            Route::view('/list-group',    'dashboard.base.list-group');
            Route::view('/navs',          'dashboard.base.navs');

            Route::view('/pagination',    'dashboard.base.pagination');
            Route::view('/popovers',      'dashboard.base.popovers');
            Route::view('/progress',      'dashboard.base.progress');
            Route::view('/scrollspy',     'dashboard.base.scrollspy');

            Route::view('/switches',      'dashboard.base.switches');
            Route::view('/tables',        'dashboard.base.tables');
            Route::view('/tabs',          'dashboard.base.tabs');
            Route::view('/tooltips',      'dashboard.base.tooltips');
        });
        Route::prefix('buttons')->group(function () {
            Route::view('/buttons',       'dashboard.buttons.buttons');
            Route::view('/button-group',  'dashboard.buttons.button-group');
            Route::view('/dropdowns',     'dashboard.buttons.dropdowns');
            Route::view('/brand-buttons', 'dashboard.buttons.brand-buttons');
        });
        Route::prefix('icon')->group(function () {  // word: "icons" - not working as part of adress
            Route::view('/coreui-icons',  'dashboard.icons.coreui-icons');
            Route::view('/flags',         'dashboard.icons.flags');
            Route::view('/brands',        'dashboard.icons.brands');
        });
        Route::prefix('notifications')->group(function () {
            Route::view('/alerts',        'dashboard.notifications.alerts');
            Route::view('/badge',         'dashboard.notifications.badge');
            Route::view('/modals',        'dashboard.notifications.modals');
        });
    });

    Route::group(['middleware' => ['role:admin']], function () {
        Route::resource('users',            UsersController::class)->except(['create', 'store']);
        Route::resource('roles',            RolesController::class);
        Route::resource('mail',             MailController::class);
        Route::get('prepareSend/{id}',      [MailController::class, 'prepareSend'])->name('prepareSend');
        Route::post('mailSend/{id}',        [MailController::class, 'send'])->name('mailSend');
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

        Route::prefix('media')->group(function () {
            Route::get('/',                 [MediaController::class, 'index'])->name('media.folder.index');
            Route::get('/folder/store',     [MediaController::class, 'folderAdd'])->name('media.folder.add');
            Route::post('/folder/update',   [MediaController::class, 'folderUpdate'])->name('media.folder.update');
            Route::get('/folder',           [MediaController::class, 'folder'])->name('media.folder');
            Route::post('/folder/move',     [MediaController::class, 'folderMove'])->name('media.folder.move');
            Route::post('/folder/delete',   [MediaController::class, 'folderDelete'])->name('media.folder.delete');;

            Route::post('/file/store',      [MediaController::class, 'fileAdd'])->name('media.file.add');
            Route::get('/file',             [MediaController::class, 'file']);
            Route::post('/file/delete',     [MediaController::class, 'fileDelete'])->name('media.file.delete');
            Route::post('/file/update',     [MediaController::class, 'fileUpdate'])->name('media.file.update');
            Route::post('/file/move',       [MediaController::class, 'fileMove'])->name('media.file.move');
            Route::post('/file/cropp',      [MediaController::class, 'cropp']);
            Route::get('/file/copy',        [MediaController::class, 'fileCopy'])->name('media.file.copy');
        });
    });
});
