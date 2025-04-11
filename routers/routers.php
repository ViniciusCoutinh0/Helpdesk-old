<?php

use App\Common\Route;

Route::init(defaultUrl());
Route::setDefaultNamespace('App\Http\Controllers');

#Login
Route::get('/authenticate/signout', 'AuthController:signOut', 'user.signOut');
Route::post('/authenticate/signin', 'AuthController:signIn', 'user.signIn');

#Ticket
Route::get('/', 'TicketController:view', 'ticket.home');
Route::get('/ticket/id/{ticket_id}', 'TicketController:show', 'ticket.show');
Route::get('/ticket/create/user/{user_id}', 'TicketController:viewCreate', 'ticket.create');

Route::post('/ticket/id/{ticket_id}/commit', 'TicketController:reply', 'ticket.commit');
Route::post('/ticket/create/user/{user_id}/new/ticket', 'TicketController:create', 'ticket.new');

#Account
Route::get('/account/user/{user_id}', 'AccountController:viewAccount', 'account.view');
Route::get('/account/user/{user_id}/change/password', 'AccountController:viewChangePassword', 'get.change.password');
Route::get('/account/user/{user_id}/change/avatar', 'AccountController:viewChangeAvatar', 'get.change.avatar');

Route::post('/account/user/{user_id}/change/password/true', 'AccountController:changePassword', 'post.change.password');
Route::post('/account/user/{user_id}/change/avatar/true', 'AccountController:changeAvatar', 'post.change.avatar');

#Admin
Route::get('/admin/listing/users', 'AdminController:listUsers', 'get.list.user');
Route::get('/admin/listing/sectors', 'AdminController:listSector', 'get.list.sector');
Route::get('/admin/add/user', 'AdminController:viewAddUser', 'get.add.user');
Route::get('/admin/update/user/{user_id}', 'AdminController:viewUpdateUser', 'get.update.user');
Route::get('/admin/add/sector', 'AdminController:viewAddSector', 'get.add.sector');
Route::get('/admin/update/sector/{sector_id}', 'AdminController:viewUpdateSector', 'get.update.sector');
Route::get('/admin/all/tickets', 'AdminController:reportTickets', 'get.all.tickets');
Route::get('/admin/all/tickets/csv/{first}/{last}', 'AdminController:generateCsv', 'get.all.csv');

Route::post('/admin/add/user/true', 'AdminController:addUser', 'post.add.user');
Route::post('/admin/update/user/{user_id}/true', 'AdminController:updateDataUser', 'post.update.user');
Route::post('/admin/add/sector/true', 'AdminController:addSector', 'post.add.sector');
Route::post('/admin/update/sector/{sector_id}/true', 'AdminController:updateDataSector', 'post.update.sector');
Route::post('/admin/all/by/period', 'AdminController:reportTicketsSend', 'post.all.tickets');

#AjaxRequest
Route::post('/request/type/category', 'HttpController:category', 'post.http.category');
Route::post('/request/type/fields', 'HttpController:fields', 'post.http.category');
Route::post('/request/type/entity', 'HttpController:entity', 'post.http.entity');
Route::post('/ajax/rating', 'AjaxController:ticketRating', 'ajax.rating.ticket');
Route::post('/ajax/solved', 'AjaxController:ticketSolved', 'ajax.solved.ticket');

Route::start();
