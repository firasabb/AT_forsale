<?php

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

Route::get('/', 'WelcomeController@index')->name('main.index');

Route::post('/search', 'WelcomeController@search')->name('main.search');
Route::get('/search', 'WelcomeController@searchResults')->name('main.searchResults');

Auth::routes();

// Check Username AJAX

Route::post('/register/checkusername', 'Auth\RegisterController@checkusername')->name('checkusername');

//

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/admin/dashboard', 'AdminController@index')->name('admin.dashboard');

// Admin / Users
Route::get('/admin/dashboard/users', 'AdminController@indexUsers')->name('admin.index.users');
Route::delete('/admin/dashboard/user/{id}', 'AdminController@destroyUser')->name('admin.delete.user');
Route::get('/admin/dashboard/user/{id}', 'AdminController@showUser')->name('admin.show.user');
Route::put('/admin/dashboard/user/{id}', 'AdminController@editUser')->name('admin.edit.user');
Route::post('/admin/dashboard/users/add', 'AdminController@addUser')->name('admin.add.user');
Route::post('/admin/dashboard/users/search', 'AdminController@searchUsers')->name('admin.search.users');
Route::get('/admin/dashboard/user/{id}/generate/password', 'AdminController@generatePassword')->name('admin.generate.password.user');


// Admin / Roles

Route::get('/admin/dashboard/roles', 'AdminController@indexRoles')->name('admin.index.roles');
Route::delete('/admin/dashboard/role/{id}', 'AdminController@destroyRole')->name('admin.delete.role');
Route::get('/admin/dashboard/role/{id}', 'AdminController@showRole')->name('admin.show.role');
Route::put('/admin/dashboard/role/{id}', 'AdminController@editRole')->name('admin.edit.role');
Route::post('/admin/dashboard/roles', 'AdminController@addRole')->name('admin.add.role');


// Admin / Permissions

Route::get('/admin/dashboard/permissions', 'AdminController@indexPermissions')->name('admin.index.permissions');
Route::delete('/admin/dashboard/permission/{id}', 'AdminController@destroyPermission')->name('admin.delete.permission');
Route::get('/admin/dashboard/permission/{id}', 'AdminController@showPermission')->name('admin.show.permission');
Route::put('/admin/dashboard/permission/{id}', 'AdminController@editPermission')->name('admin.edit.permission');
Route::post('/admin/dashboard/permissions', 'AdminController@addPermission')->name('admin.add.permission');


// Admin / Arts

Route::get('/admin/dashboard/arts/', 'ArtController@adminIndex')->middleware('role:admin|moderator')->name('admin.index.arts');
Route::delete('/admin/dashboard/art/{id}', 'ArtController@adminDestroy')->middleware('role:admin|moderator')->name('admin.delete.art');
Route::get('/admin/dashboard/art/{id}', 'ArtController@adminShow')->middleware('role:admin|moderator')->name('admin.show.art');
Route::put('/admin/dashboard/art/{id}', 'ArtController@adminEdit')->middleware('role:admin|moderator')->name('admin.edit.art');
Route::post('/admin/dashboard/art/', 'ArtController@adminAdd')->middleware('role:admin|moderator')->name('admin.add.art');
Route::post('/admin/dashboard/arts/search', 'ArtController@adminSearchArts')->middleware('role:admin|moderator')->name('admin.search.arts');

// Admin Approve Arts

Route::get('/admin/dashboard/approve/arts', 'ArtController@indexToApprove')->middleware('role:admin|moderator')->name('admin.index.approve.arts');
Route::post('/admin/dashboard/approve/arts/{id}', 'ArtController@adminApprove')->middleware('role:admin|moderator')->name('admin.approve.art');


// Admin / Downloads

Route::post('/admin/download/{artId}', 'DownloadController@adminAdd')->middleware('role:admin|moderator')->name('admin.download.add');
Route::delete('/admin/download/{id}', 'DownloadController@adminDelete')->middleware('role:admin|moderator')->name('admin.download.delete');


// Admin / Contests

Route::get('/admin/dashboard/contests/', 'ContestController@adminIndex')->middleware('role:admin|moderator')->name('admin.index.contests');
Route::delete('/admin/dashboard/contest/{id}', 'ContestController@adminDestroy')->middleware('role:admin|moderator')->name('admin.delete.contest');
Route::get('/admin/dashboard/contest/{id}', 'ContestController@adminShow')->middleware('role:admin|moderator')->name('admin.show.contest');
Route::put('/admin/dashboard/contest/{id}', 'ContestController@adminEdit')->middleware('role:admin|moderator')->name('admin.edit.contest');
Route::post('/admin/dashboard/contest/', 'ContestController@adminAdd')->middleware('role:admin|moderator')->name('admin.add.contest');
Route::post('/admin/dashboard/contests/search', 'ContestController@adminSearchContests')->middleware('role:admin|moderator')->name('admin.search.contests');


// Admin / Tags

Route::get('/admin/dashboard/tags/', 'TagController@adminIndex')->middleware('role:admin|moderator')->name('admin.index.tags');
Route::delete('/admin/dashboard/tag/{id}', 'TagController@adminDestroy')->middleware('role:admin|moderator')->name('admin.delete.tag');
Route::get('/admin/dashboard/tag/{id}', 'TagController@adminShow')->middleware('role:admin|moderator')->name('admin.show.tag');
Route::put('/admin/dashboard/tag/{id}', 'TagController@adminEdit')->middleware('role:admin|moderator')->name('admin.edit.tag');
Route::post('/admin/dashboard/tag/', 'TagController@adminAdd')->middleware('role:admin|moderator')->name('admin.add.tag');
Route::post('/admin/dashboard/tags/search', 'TagController@adminSearchTags')->middleware('role:admin|moderator')->name('admin.search.tags');


// Admin / Categories

Route::get('/admin/dashboard/categories/', 'CategoryController@adminIndex')->middleware('role:admin|moderator')->name('admin.index.categories');
Route::delete('/admin/dashboard/category/{id}', 'CategoryController@adminDestroy')->middleware('role:admin|moderator')->name('admin.delete.category');
Route::get('/admin/dashboard/category/{id}', 'CategoryController@adminShow')->middleware('role:admin|moderator')->name('admin.show.category');
Route::put('/admin/dashboard/category/{id}', 'CategoryController@adminEdit')->middleware('role:admin|moderator')->name('admin.edit.category');
Route::post('/admin/dashboard/category/', 'CategoryController@adminAdd')->middleware('role:admin|moderator')->name('admin.add.category');
Route::post('/admin/dashboard/categories/search', 'CategoryController@adminSearchCategories')->middleware('role:admin|moderator')->name('admin.search.categories');


// Admin / Types

Route::get('/admin/dashboard/types/', 'TypeController@adminIndex')->middleware('role:admin|moderator')->name('admin.index.types');
Route::delete('/admin/dashboard/categorie/{id}', 'TypeController@adminDestroy')->middleware('role:admin|moderator')->name('admin.delete.type');
Route::get('/admin/dashboard/type/{id}', 'TypeController@adminShow')->middleware('role:admin|moderator')->name('admin.show.type');
Route::put('/admin/dashboard/type/{id}', 'TypeController@adminEdit')->middleware('role:admin|moderator')->name('admin.edit.type');
Route::post('/admin/dashboard/type/', 'TypeController@adminAdd')->middleware('role:admin|moderator')->name('admin.add.type');
Route::post('/admin/dashboard/types/search', 'TypeController@adminSearchTypes')->middleware('role:admin|moderator')->name('admin.search.types');


// Admin / Comments

Route::get('/admin/dashboard/comments/', 'CommentController@adminIndex')->middleware('role:admin|moderator')->name('admin.index.comments');
Route::delete('/admin/dashboard/comment/{id}', 'CommentController@adminDestroy')->middleware('role:admin|moderator')->name('admin.delete.comment');
Route::get('/admin/dashboard/comment/{id}', 'CommentController@adminShow')->middleware('role:admin|moderator')->name('admin.show.comment');
Route::put('/admin/dashboard/comment/{id}', 'CommentController@adminEdit')->middleware('role:admin|moderator')->name('admin.edit.comment');
Route::post('/admin/dashboard/comment/', 'CommentController@adminAdd')->middleware('role:admin|moderator')->name('admin.add.comment');
Route::post('/admin/dashboard/comments/search', 'CommentController@adminSearchcomments')->middleware('role:admin|moderator')->name('admin.search.comments');



// Admin / Reports

Route::get('/admin/dashboard/reports/', 'ReportController@adminIndex')->middleware('role:admin|moderator')->name('admin.index.reports');
Route::delete('/admin/dashboard/report/{id}', 'ReportController@adminDestroy')->middleware('role:admin|moderator')->name('admin.delete.report');
Route::get('/admin/dashboard/report/{id}', 'ReportController@adminShow')->middleware('role:admin|moderator')->name('admin.show.report');
Route::put('/admin/dashboard/report/{id}', 'ReportController@adminEdit')->middleware('role:admin|moderator')->name('admin.edit.report');
Route::post('/admin/dashboard/report/', 'ReportController@adminAdd')->middleware('role:admin|moderator')->name('admin.add.report');
Route::post('/admin/dashboard/report/search', 'ReportController@adminSearchReports')->middleware('role:admin|moderator')->name('admin.search.reports');


// Arts add
Route::get('/add/art/{type?}', 'ArtController@create')->middleware('role:user')->name('create.art');
Route::post('/add/art/{type}', 'ArtController@store')->middleware('role:user')->name('store.art');


// Contest

Route::get('/add/contest', 'ContestController@create')->name('create.contest');
Route::post('/add/contest', 'ContestController@store')->name('store.contest');


// Art

Route::get('/art/{url}', 'ArtController@show')->name('show.art');



// Users

Route::get('/artist/{username}', 'UserController@showProfile')->name('user.profile.show');
Route::get('artist/{username}/setup', 'UserController@setupProfilePage')->name('user.profile.setup.show');
Route::put('artist/{username}/setup', 'UserController@setupProfileRequest')->name('user.profile.setup.request');
Route::get('artist/{username}/changepassword', 'UserController@changePasswordPage')->name('user.profile.password.show');
Route::post('artist/{username}/changepassword', 'UserController@changePasswordRequest')->name('user.profile.password.request');


// Tags

Route::get('/tags', 'TagController@index')->middleware('role:user')->name('index.tags');

// Comments

Route::post('/comment/create/{encryptedId}', 'CommentController@store')->middleware('role:user')->name('add.comment');


// Reports

Route::post('/reports/add/{type}', 'ReportController@store')->middleware('role:user')->name('add.report');


// Get Tags AJAX

Route::post('/suggest/tags/', 'TagController@suggestTags')->middleware('role:user')->name('suggest.tags');

// Downloads

Route::get('/download/{id}', 'DownloadController@downloadDownload')->middleware('role:user')->name('download.download');