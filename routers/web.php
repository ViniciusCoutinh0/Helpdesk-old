<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\AdminController;
use Pecee\SimpleRouter\SimpleRouter as Route;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AppController;
use App\Http\Controllers\HttpController;

Route::get('/', [AppController::class, 'home'])->name('app.home');
Route::get('/list/all/user/{user}/by/state/{state}', [AppController::class, 'list'])->name('app.list.state');
Route::post('/auth/sign/in', [AuthController::class, 'signIn'])->name('auth.sigin');
Route::get('/auth/sign/out', [AuthController::class, 'signOut'])->name('auth.signout');

Route::group(['prefix' => 'ticket'], function () {
    Route::get('/state/{state}', [TicketController::class, 'show'])->name('ticket.all.state');
    Route::get('/by/id/{id}', [TicketController::class, 'show'])->name('ticket.show');
    Route::get('/create/user/{user}', [TicketController::class, 'viewStore'])->name('ticket.store.view');
    Route::form('/create/user/{user}/new', [TicketController::class, 'store'])->name('ticket.store');
    Route::post('/commit/by/{id}', [TicketController::class, 'commitStore'])->name('commit.store');
});

Route::group(['prefix' => 'account'], function () {
    Route::get('/user/{user}', [AccountController::class, 'viewAccount'])->name('account.view');
    Route::get('/user/{user}/update/password', [AccountController::class, 'viewPassword'])->name('account.password');
    Route::post('/user/{user}/update/password/true', [AccountController::class, 'storePassword'])->name('account.store.password');
});

Route::group(['prefix' => 'admin'], function () {
    Route::get('/list/all/users', [AdminController::class, 'listUsers'])->name('admin.list.all.users');
    Route::get('/list/all/sectors', [AdminController::class, 'listSections'])->name('admin.list.all.sectors');

    Route::get('/create/new/user', [AdminController::class, 'viewCreateUser'])->name('admin.view.create.user');
    Route::post('/create/new/user/true', [AdminController::class, 'createUser'])->name('admin.post.create.user');
    Route::get('/update/user/{user}', [AdminController::class, 'viewUpdateUser'])->name('admin.view.update.user');
    Route::post('/update/user/{user}/true', [AdminController::class, 'updateUser'])->name('admin.post.update.user');

    Route::get('/create/new/sector', [AdminController::class, 'viewCreateSector'])->name('admin.view.create.sector');
    Route::post('/create/new/sector/true', [AdminController::class, 'createSector'])->name('admin.post.create.sector');
    Route::get('/update/sector/{sector}', [AdminController::class, 'viewUpdateSector'])->name('admin.view.update.sector');
    Route::post('/update/sector/{sector}/true', [AdminController::class, 'updateSector'])->name('admin.post.update.sector');

    Route::get('/report/tickets', [AdminController::class, 'viewCreateReport'])->name('admin.view.report');
    Route::post('/report/tickets/true', [AdminController::class, 'createReport'])->name('admin.create.report');
    Route::get('/report/output/file/{start_date}/{end_date}/{departament}', [AdminController::class, 'outputReport'])->name('admin.output.report');
});

Route::group(['prefix' => 'request'], function () {
    Route::post('/type/category', [HttpController::class, 'category'])->name('request.category');
    Route::post('/type/fields', [HttpController::class, 'fields'])->name('request.category');
    Route::post('/type/entity', [HttpController::class, 'entity'])->name('request.entity');
});
