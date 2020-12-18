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

Auth::routes(['verify' => true]);

// Check Username AJAX

Route::post('/register/checkusername', 'Auth\RegisterController@checkusername')->name('checkusername');

//

Route::get('/home', 'WelcomeController@index')->name('home');
Route::get('/admin/dashboard', 'Admin\AdminController@index')->name('admin.dashboard');

// Admin / Users
Route::get('/admin/dashboard/users/{order?}/{desc?}', 'Admin\AdminUserController@indexUsers')->middleware('role:admin')->name('admin.index.users');
Route::post('/admin/dashboard/users', 'Admin\AdminUserController@filterUsers')->middleware('role:admin')->name('admin.filter.users');
Route::delete('/admin/dashboard/user/{id}', 'Admin\AdminUserController@destroyUser')->middleware('role:admin')->name('admin.delete.user');
Route::get('/admin/dashboard/user/{id}', 'Admin\AdminUserController@showUser')->middleware('role:admin')->name('admin.show.user');
Route::put('/admin/dashboard/user/{id}', 'Admin\AdminUserController@editUser')->middleware('role:admin')->name('admin.edit.user');
Route::post('/admin/dashboard/users/add', 'Admin\AdminUserController@addUser')->middleware('role:admin')->name('admin.add.user');
Route::post('/admin/dashboard/users/search', 'Admin\AdminUserController@searchUsers')->middleware('role:admin')->name('admin.search.users');
Route::get('/admin/dashboard/user/{id}/generate/password', 'Admin\AdminUserController@generatePassword')->middleware('role:admin')->name('admin.generate.password.user');


// Admin / Roles

Route::get('/admin/dashboard/roles', 'Admin\AdminController@indexRoles')->middleware('role:admin')->name('admin.index.roles');
Route::delete('/admin/dashboard/role/{id}', 'Admin\AdminController@destroyRole')->middleware('role:admin')->name('admin.delete.role');
Route::get('/admin/dashboard/role/{id}', 'Admin\AdminController@showRole')->middleware('role:admin')->name('admin.show.role');
Route::put('/admin/dashboard/role/{id}', 'Admin\AdminController@editRole')->middleware('role:admin')->name('admin.edit.role');
Route::post('/admin/dashboard/roles', 'Admin\AdminController@addRole')->middleware('role:admin')->name('admin.add.role');


// Admin / Permissions

Route::get('/admin/dashboard/permissions', 'Admin\AdminController@indexPermissions')->middleware('role:admin')->name('admin.index.permissions');
Route::delete('/admin/dashboard/permission/{id}', 'Admin\AdminController@destroyPermission')->middleware('role:admin')->name('admin.delete.permission');
Route::get('/admin/dashboard/permission/{id}', 'Admin\AdminController@showPermission')->middleware('role:admin')->name('admin.show.permission');
Route::put('/admin/dashboard/permission/{id}', 'Admin\AdminController@editPermission')->middleware('role:admin')->name('admin.edit.permission');
Route::post('/admin/dashboard/permissions', 'Admin\AdminController@addPermission')->middleware('role:admin')->name('admin.add.permission');


// Admin / Posts

Route::get('/admin/dashboard/posts/{order?}/{desc?}', 'Admin\AdminPostController@adminIndex')->middleware('role:admin|moderator')->name('admin.index.posts');
Route::delete('/admin/dashboard/post/{id}', 'Admin\AdminPostController@adminDestroy')->middleware('role:admin|moderator')->name('admin.delete.post');
Route::get('/admin/dashboard/post/{id}', 'Admin\AdminPostController@adminShow')->middleware('role:admin|moderator')->name('admin.show.post');
Route::put('/admin/dashboard/post/{id}', 'Admin\AdminPostController@adminEdit')->middleware('role:admin|moderator')->name('admin.edit.post');
Route::post('/admin/dashboard/post/', 'Admin\AdminPostController@adminAdd')->middleware('role:admin|moderator')->name('admin.add.post');
Route::post('/admin/dashboard/posts/search', 'Admin\AdminPostController@adminSearch')->middleware('role:admin|moderator')->name('admin.search.posts');
Route::post('/admin/dashboard/post/disapprove/{id}', 'Admin\AdminPostController@adminDisapprove')->middleware('role:admin|moderator')->name('admin.disapprove.post');

// Admin Approve Posts

Route::get('/admin/dashboard/approve/posts', 'Admin\AdminPostController@indexToApprove')->middleware('role:admin|moderator')->name('admin.index.approve.posts');
Route::post('/admin/dashboard/approve/posts/{id}', 'Admin\AdminPostController@adminApprove')->middleware('role:admin|moderator')->name('admin.approve.post');


// Admin / Downloads

Route::post('/admin/download/{postId}', 'DownloadController@adminAdd')->middleware('role:admin|moderator')->name('admin.download.add');
Route::delete('/admin/download/{id}', 'DownloadController@adminDelete')->middleware('role:admin|moderator')->name('admin.download.delete');
Route::get('/admin/download/{id}', 'DownloadController@adminDownloadDownload')->middleware('role:admin|moderator')->name('admin.download.download');


// Admin / Tags

Route::get('/admin/dashboard/tags/{order?}/{desc?}', 'Admin\AdminTagController@adminIndex')->middleware('role:admin|moderator')->name('admin.index.tags');
Route::delete('/admin/dashboard/tag/{id}', 'Admin\AdminTagController@adminDestroy')->middleware('role:admin|moderator')->name('admin.delete.tag');
Route::get('/admin/dashboard/tag/{id}', 'Admin\AdminTagController@adminShow')->middleware('role:admin|moderator')->name('admin.show.tag');
Route::put('/admin/dashboard/tag/{id}', 'Admin\AdminTagController@adminEdit')->middleware('role:admin|moderator')->name('admin.edit.tag');
Route::post('/admin/dashboard/tag/', 'Admin\AdminTagController@adminAdd')->middleware('role:admin|moderator')->name('admin.add.tag');
Route::post('/admin/dashboard/tags/search', 'Admin\AdminTagController@adminSearch')->middleware('role:admin|moderator')->name('admin.search.tags');
Route::get('/admin/dashboard/tag/bulk/add', 'Admin\AdminTagController@adminBulkAddForm')->middleware('role:admin|moderator')->name('admin.bulk.add.form.tags');
Route::post('/admin/dashboard/tag/bulk/add', 'Admin\AdminTagController@adminBulkAdd')->middleware('role:admin|moderator')->name('admin.bulk.add.tags');

// Admin / Categories

Route::get('/admin/dashboard/categories/{order?}/{desc?}', 'Admin\AdminCategoryController@adminIndex')->middleware('role:admin|moderator')->name('admin.index.categories');
Route::delete('/admin/dashboard/categorie/{id}', 'Admin\AdminCategoryController@adminDestroy')->middleware('role:admin|moderator')->name('admin.delete.category');
Route::get('/admin/dashboard/category/{id}', 'Admin\AdminCategoryController@adminShow')->middleware('role:admin|moderator')->name('admin.show.category');
Route::put('/admin/dashboard/category/{id}', 'Admin\AdminCategoryController@adminEdit')->middleware('role:admin|moderator')->name('admin.edit.category');
Route::post('/admin/dashboard/category/', 'Admin\AdminCategoryController@adminAdd')->middleware('role:admin|moderator')->name('admin.add.category');
Route::post('/admin/dashboard/categories/search', 'Admin\AdminCategoryController@adminSearchCategories')->middleware('role:admin|moderator')->name('admin.search.categories');


// Admin / Comments

Route::get('/admin/dashboard/comments/{order?}/{desc?}', 'Admin\AdminCommentController@adminIndex')->middleware('role:admin|moderator')->name('admin.index.comments');
Route::delete('/admin/dashboard/comment/{id}', 'Admin\AdminCommentController@adminDestroy')->middleware('role:admin|moderator')->name('admin.delete.comment');
Route::get('/admin/dashboard/comment/{id}', 'Admin\AdminCommentController@adminShow')->middleware('role:admin|moderator')->name('admin.show.comment');
Route::put('/admin/dashboard/comment/{id}', 'Admin\AdminCommentController@adminEdit')->middleware('role:admin|moderator')->name('admin.edit.comment');
Route::post('/admin/dashboard/comment/', 'Admin\AdminCommentController@adminAdd')->middleware('role:admin|moderator')->name('admin.add.comment');
Route::post('/admin/dashboard/comments/search', 'Admin\AdminCommentController@adminSearch')->middleware('role:admin|moderator')->name('admin.search.comments');



// Admin / Reports

Route::get('/admin/dashboard/reports/', 'Admin\AdminReportController@adminIndex')->middleware('role:admin|moderator')->name('admin.index.reports');
Route::delete('/admin/dashboard/report/{id}', 'Admin\AdminReportController@adminDestroy')->middleware('role:admin|moderator')->name('admin.delete.report');
Route::get('/admin/dashboard/report/{id}', 'Admin\AdminReportController@adminShow')->middleware('role:admin|moderator')->name('admin.show.report');
Route::put('/admin/dashboard/report/{id}', 'Admin\AdminReportController@adminEdit')->middleware('role:admin|moderator')->name('admin.edit.report');
Route::post('/admin/dashboard/report/', 'Admin\AdminReportController@adminAdd')->middleware('role:admin|moderator')->name('admin.add.report');
Route::post('/admin/dashboard/report/search', 'Admin\AdminReportController@adminSearch')->middleware('role:admin|moderator')->name('admin.search.reports');

// Admin / Licenses

Route::get('/admin/dashboard/licenses/', 'Admin\AdminLicenseController@adminIndex')->middleware('role:admin|moderator')->name('admin.index.licenses');
Route::delete('/admin/dashboard/license/{id}', 'Admin\AdminLicenseController@adminDestroy')->middleware('role:admin|moderator')->name('admin.delete.license');
Route::get('/admin/dashboard/license/{id}', 'Admin\AdminLicenseController@adminShow')->middleware('role:admin|moderator')->name('admin.show.license');
Route::put('/admin/dashboard/license/{id}', 'Admin\AdminLicenseController@adminEdit')->middleware('role:admin|moderator')->name('admin.edit.license');
Route::post('/admin/dashboard/license/', 'Admin\AdminLicenseController@adminAdd')->middleware('role:admin|moderator')->name('admin.add.license');
Route::post('/admin/dashboard/licenses/search', 'Admin\AdminLicenseController@adminSearch')->middleware('role:admin|moderator')->name('admin.search.licenses');

// Admin / External Ads

Route::get('/admin/dashboard/externalads/', 'Admin\AdminExternalAdController@adminIndex')->middleware('role:admin|moderator')->name('admin.index.externalads');
Route::delete('/admin/dashboard/externalad/{id}', 'Admin\AdminExternalAdController@adminDestroy')->middleware('role:admin|moderator')->name('admin.delete.externalad');
Route::get('/admin/dashboard/externalad/{id}', 'Admin\AdminExternalAdController@adminShow')->middleware('role:admin|moderator')->name('admin.show.externalad');
Route::put('/admin/dashboard/externalad/{id}', 'Admin\AdminExternalAdController@adminEdit')->middleware('role:admin|moderator')->name('admin.edit.externalad');
Route::post('/admin/dashboard/externalad/', 'Admin\AdminExternalAdController@adminAdd')->middleware('role:admin|moderator')->name('admin.add.externalad');
Route::post('/admin/dashboard/externalads/search', 'Admin\AdminExternalAdController@adminSearch')->middleware('role:admin|moderator')->name('admin.search.externalads');


// Admin / Contact Messages

Route::get('/admin/dashboard/contactmessages/', 'Admin\AdminContactMessageController@adminIndex')->middleware('role:admin|moderator')->name('admin.index.contactmessages');
Route::delete('/admin/dashboard/contactmessage/{id}', 'Admin\AdminContactMessageController@adminDestroy')->middleware('role:admin|moderator')->name('admin.delete.contactmessage');
Route::get('/admin/dashboard/contactmessage/{id}', 'Admin\AdminContactMessageController@adminShow')->middleware('role:admin|moderator')->name('admin.show.contactmessage');
Route::put('/admin/dashboard/contactmessage/{id}', 'Admin\AdminContactMessageController@adminEdit')->middleware('role:admin|moderator')->name('admin.edit.contactmessage');
Route::post('/admin/dashboard/contactmessage/', 'Admin\AdminContactMessageController@adminAdd')->middleware('role:admin|moderator')->name('admin.add.contactmessage');
Route::post('/admin/dashboard/contactmessages/search', 'Admin\AdminContactMessageController@adminSearch')->middleware('role:admin|moderator')->name('admin.search.contactmessages');


// Admin / Email Campaigns

Route::get('/admin/dashboard/emailcampaigns/', 'Admin\AdminEmailCampaignController@adminIndex')->middleware('role:admin|moderator')->name('admin.index.emailcampaigns');
Route::delete('/admin/dashboard/emailcampaign/{id}', 'Admin\AdminEmailCampaignController@adminDestroy')->middleware('role:admin|moderator')->name('admin.delete.emailcampaign');
Route::get('/admin/dashboard/emailcampaign/{id}', 'Admin\AdminEmailCampaignController@adminShow')->middleware('role:admin|moderator')->name('admin.show.emailcampaign');
Route::put('/admin/dashboard/emailcampaign/{id}', 'Admin\AdminEmailCampaignController@adminEdit')->middleware('role:admin|moderator')->name('admin.edit.emailcampaign');
Route::post('/admin/dashboard/emailcampaign/', 'Admin\AdminEmailCampaignController@adminAdd')->middleware('role:admin|moderator')->name('admin.add.emailcampaign');
Route::post('/admin/dashboard/emailcampaigns/search', 'Admin\AdminEmailCampaignController@adminSearch')->middleware('role:admin|moderator')->name('admin.search.emailcampaigns');


// Admin / Pages

Route::get('/admin/dashboard/pages/{order?}/{desc?}', 'Admin\AdminPageController@adminIndex')->middleware('role:admin|moderator')->name('admin.index.pages');
Route::delete('/admin/dashboard/page/{id}', 'Admin\AdminPageController@adminDestroy')->middleware('role:admin|moderator')->name('admin.delete.page');
Route::get('/admin/dashboard/page/{id}', 'Admin\AdminPageController@adminShow')->middleware('role:admin|moderator')->name('admin.show.page');
Route::put('/admin/dashboard/page/{id}', 'Admin\AdminPageController@adminEdit')->middleware('role:admin|moderator')->name('admin.edit.page');
Route::post('/admin/dashboard/page/', 'Admin\AdminPageController@adminAdd')->middleware('role:admin|moderator')->name('admin.add.page');
Route::post('/admin/dashboard/pages/search', 'Admin\AdminPageController@adminSearch')->middleware('role:admin|moderator')->name('admin.search.pages');


// Admin / Send Emails
Route::get('/admin/email/send', 'Admin\AdminController@sendEmailForm')->middleware('role:admin|moderator')->name('admin.send.emailForm');
Route::post('/admin/email/send', 'Admin\AdminController@sendEmail')->middleware('role:admin|moderator')->name('admin.send.email');

// Posts Add
Route::get('/add/post', 'PostController@create')->middleware('auth', 'verified')->name('create.post');
Route::post('/add/post', 'PostController@store')->middleware('auth', 'verified')->name('store.post');



// Post

Route::get('/post/{url}', 'PostController@show')->name('show.post');


// Search

//Route::post('/search', 'WelcomeController@search')->name('main.search');
Route::post('/search', 'WelcomeController@searchPost')->name('main.post.search');
Route::get('/search/category/{category}/keyword/{keyword}', 'WelcomeController@searchGet')->name('main.get.search');
Route::get('/category/{category?}', 'WelcomeController@searchCategories')->name('main.search.categories');
Route::get('/tag/{tag?}', 'WelcomeController@searchTags')->name('main.search.tags');

// Users
// public
Route::get('/u/{username}', 'UserController@showProfile')->name('user.profile.show');
// private
Route::get('/dashboard', 'UserController@dashboard')->middleware('role:user')->name('user.dashboard');
Route::get('/dashboard/myprofile', 'UserController@showMyProfile')->middleware('role:user')->name('user.profile.dashboard.show');
Route::get('/dashboard/setup', 'UserController@setupProfilePage')->middleware('role:user')->name('user.setup.show');
Route::put('/dashboard/setup', 'UserController@setupProfileRequest')->middleware('role:user')->name('user.setup.request');
Route::get('/dashboard/changepassword', 'UserController@changePasswordPage')->middleware('role:user')->name('user.password.show');
Route::post('/dashboard/changepassword', 'UserController@changePasswordRequest')->middleware('role:user')->name('user.password.request');
Route::get('/dashboard/myposts', 'UserController@myPostsPage')->middleware('role:user')->name('user.posts.show');
Route::delete('/dashboard/post/delete/{id}', 'PostController@destroy')->middleware('role:user')->name('user.delete.post');
// Send Verification Email
Route::get('/user/send/verificationemail', 'UserController@sendVerificationEmail')->middleware('role:user')->name('user.send.verification.email');


// Tags

Route::get('/tags', 'TagController@index')->middleware('auth')->name('index.tags');

// Comments

Route::post('/comment/create/{encryptedId}', 'CommentController@store')->middleware('auth')->name('add.comment');
Route::delete('/comment/delete/{id}', 'CommentController@destroy')->middleware('auth')->name('delete.comment');

// Reports

Route::post('/reports/add/{type}', 'ReportController@store')->middleware('auth')->name('add.report');

// Get Tags AJAX

Route::post('/suggest/tags/', 'TagController@suggestTags')->middleware('auth')->name('suggest.tags');

// Downloads

Route::post('/download/download', 'DownloadController@downloadDownload')->name('download.download');

// Contact Us Page

Route::get('/page/contactus', 'ContactMessageController@create')->name('create.contactus');
Route::post('/page/contactus', 'ContactMessageController@store')->name('store.contactus');

// Pages

Route::get('/page/{url}', 'PageController@showPage')->name('show.page');