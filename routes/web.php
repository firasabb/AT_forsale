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


// Admin / Posts

Route::get('/admin/dashboard/posts/', 'PostController@adminIndex')->middleware('role:admin|moderator')->name('admin.index.posts');
Route::delete('/admin/dashboard/post/{id}', 'PostController@adminDestroy')->middleware('role:admin|moderator')->name('admin.delete.post');
Route::get('/admin/dashboard/post/{id}', 'PostController@adminShow')->middleware('role:admin|moderator')->name('admin.show.post');
Route::put('/admin/dashboard/post/{id}', 'PostController@adminEdit')->middleware('role:admin|moderator')->name('admin.edit.post');
Route::post('/admin/dashboard/post/', 'PostController@adminAdd')->middleware('role:admin|moderator')->name('admin.add.post');
Route::post('/admin/dashboard/posts/search', 'PostController@adminSearchPosts')->middleware('role:admin|moderator')->name('admin.search.posts');
Route::post('/admin/dashboard/post/disapprove/{id}', 'PostController@adminDisapprove')->middleware('role:admin|moderator')->name('admin.disapprove.post');

// Admin Approve Posts

Route::get('/admin/dashboard/approve/posts', 'PostController@indexToApprove')->middleware('role:admin|moderator')->name('admin.index.approve.posts');
Route::post('/admin/dashboard/approve/posts/{id}', 'PostController@adminApprove')->middleware('role:admin|moderator')->name('admin.approve.post');


// Admin / Downloads

Route::post('/admin/download/{postId}', 'DownloadController@adminAdd')->middleware('role:admin|moderator')->name('admin.download.add');
Route::delete('/admin/download/{id}', 'DownloadController@adminDelete')->middleware('role:admin|moderator')->name('admin.download.delete');
Route::get('/admin/download/{id}', 'DownloadController@adminDownloadDownload')->middleware('role:admin|moderator')->name('admin.download.download');


// Admin / Tags

Route::get('/admin/dashboard/tags/', 'TagController@adminIndex')->middleware('role:admin|moderator')->name('admin.index.tags');
Route::delete('/admin/dashboard/tag/{id}', 'TagController@adminDestroy')->middleware('role:admin|moderator')->name('admin.delete.tag');
Route::get('/admin/dashboard/tag/{id}', 'TagController@adminShow')->middleware('role:admin|moderator')->name('admin.show.tag');
Route::put('/admin/dashboard/tag/{id}', 'TagController@adminEdit')->middleware('role:admin|moderator')->name('admin.edit.tag');
Route::post('/admin/dashboard/tag/', 'TagController@adminAdd')->middleware('role:admin|moderator')->name('admin.add.tag');
Route::post('/admin/dashboard/tags/search', 'TagController@adminSearchTags')->middleware('role:admin|moderator')->name('admin.search.tags');
Route::get('/admin/dashboard/tag/bulk/add', 'TagController@adminBulkAddForm')->middleware('role:admin|moderator')->name('admin.bulk.add.form.tags');
Route::post('/admin/dashboard/tag/bulk/add', 'TagController@adminBulkAdd')->middleware('role:admin|moderator')->name('admin.bulk.add.tags');

// Admin / Categories

Route::get('/admin/dashboard/categories/', 'CategoryController@adminIndex')->middleware('role:admin|moderator')->name('admin.index.categories');
Route::delete('/admin/dashboard/categorie/{id}', 'CategoryController@adminDestroy')->middleware('role:admin|moderator')->name('admin.delete.category');
Route::get('/admin/dashboard/category/{id}', 'CategoryController@adminShow')->middleware('role:admin|moderator')->name('admin.show.category');
Route::put('/admin/dashboard/category/{id}', 'CategoryController@adminEdit')->middleware('role:admin|moderator')->name('admin.edit.category');
Route::post('/admin/dashboard/category/', 'CategoryController@adminAdd')->middleware('role:admin|moderator')->name('admin.add.category');
Route::post('/admin/dashboard/categories/search', 'CategoryController@adminSearchCategories')->middleware('role:admin|moderator')->name('admin.search.categories');


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

// Admin / Licenses

Route::get('/admin/dashboard/licenses/', 'LicenseController@adminIndex')->middleware('role:admin|moderator')->name('admin.index.licenses');
Route::delete('/admin/dashboard/license/{id}', 'LicenseController@adminDestroy')->middleware('role:admin|moderator')->name('admin.delete.license');
Route::get('/admin/dashboard/license/{id}', 'LicenseController@adminShow')->middleware('role:admin|moderator')->name('admin.show.license');
Route::put('/admin/dashboard/license/{id}', 'LicenseController@adminEdit')->middleware('role:admin|moderator')->name('admin.edit.license');
Route::post('/admin/dashboard/license/', 'LicenseController@adminAdd')->middleware('role:admin|moderator')->name('admin.add.license');
Route::post('/admin/dashboard/licenses/search', 'LicenseController@adminSearchLicenses')->middleware('role:admin|moderator')->name('admin.search.licenses');

// Admin / External Ads

Route::get('/admin/dashboard/externalads/', 'ExternalAdController@adminIndex')->middleware('role:admin|moderator')->name('admin.index.externalads');
Route::delete('/admin/dashboard/externalad/{id}', 'ExternalAdController@adminDestroy')->middleware('role:admin|moderator')->name('admin.delete.externalad');
Route::get('/admin/dashboard/externalad/{id}', 'ExternalAdController@adminShow')->middleware('role:admin|moderator')->name('admin.show.externalad');
Route::put('/admin/dashboard/externalad/{id}', 'ExternalAdController@adminEdit')->middleware('role:admin|moderator')->name('admin.edit.externalad');
Route::post('/admin/dashboard/externalad/', 'ExternalAdController@adminAdd')->middleware('role:admin|moderator')->name('admin.add.externalad');
Route::post('/admin/dashboard/externalads/search', 'ExternalAdController@adminSearchExternalAds')->middleware('role:admin|moderator')->name('admin.search.externalads');


// Admin / Contact Messages

Route::get('/admin/dashboard/contactmessages/', 'ContactMessageController@adminIndex')->middleware('role:admin|moderator')->name('admin.index.contactmessages');
Route::delete('/admin/dashboard/contactmessage/{id}', 'ContactMessageController@adminDestroy')->middleware('role:admin|moderator')->name('admin.delete.contactmessage');
Route::get('/admin/dashboard/contactmessage/{id}', 'ContactMessageController@adminShow')->middleware('role:admin|moderator')->name('admin.show.contactmessage');
Route::put('/admin/dashboard/contactmessage/{id}', 'ContactMessageController@adminEdit')->middleware('role:admin|moderator')->name('admin.edit.contactmessage');
Route::post('/admin/dashboard/contactmessage/', 'ContactMessageController@adminAdd')->middleware('role:admin|moderator')->name('admin.add.contactmessage');
Route::post('/admin/dashboard/contactmessages/search', 'ContactMessageController@adminSearchExternalAds')->middleware('role:admin|moderator')->name('admin.search.contactmessages');


// Admin / Email Campaigns

Route::get('/admin/dashboard/emailcampaigns/', 'EmailCampaignController@adminIndex')->middleware('role:admin|moderator')->name('admin.index.emailcampaigns');
Route::delete('/admin/dashboard/emailcampaign/{id}', 'EmailCampaignController@adminDestroy')->middleware('role:admin|moderator')->name('admin.delete.emailcampaign');
Route::get('/admin/dashboard/emailcampaign/{id}', 'EmailCampaignController@adminShow')->middleware('role:admin|moderator')->name('admin.show.emailcampaign');
Route::put('/admin/dashboard/emailcampaign/{id}', 'EmailCampaignController@adminEdit')->middleware('role:admin|moderator')->name('admin.edit.emailcampaign');
Route::post('/admin/dashboard/emailcampaign/', 'EmailCampaignController@adminAdd')->middleware('role:admin|moderator')->name('admin.add.emailcampaign');
Route::post('/admin/dashboard/emailcampaigns/search', 'EmailCampaignController@adminSearchEmailCampaigns')->middleware('role:admin|moderator')->name('admin.search.emailcampaigns');


// Admin / Pages

Route::get('/admin/dashboard/pages/', 'PageController@adminIndex')->middleware('role:admin|moderator')->name('admin.index.pages');
Route::delete('/admin/dashboard/page/{id}', 'PageController@adminDestroy')->middleware('role:admin|moderator')->name('admin.delete.page');
Route::get('/admin/dashboard/page/{id}', 'PageController@adminShow')->middleware('role:admin|moderator')->name('admin.show.page');
Route::put('/admin/dashboard/page/{id}', 'PageController@adminEdit')->middleware('role:admin|moderator')->name('admin.edit.page');
Route::post('/admin/dashboard/page/', 'PageController@adminAdd')->middleware('role:admin|moderator')->name('admin.add.page');
Route::post('/admin/dashboard/pages/search', 'PageController@adminSearchPages')->middleware('role:admin|moderator')->name('admin.search.pages');


// Admin / Send Emails
Route::get('/admin/email/send', 'AdminController@sendEmailForm')->middleware('role:admin|moderator')->name('admin.send.emailForm');
Route::post('/admin/email/send', 'AdminController@sendEmail')->middleware('role:admin|moderator')->name('admin.send.email');

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

Route::get('/u/{username}', 'UserController@showProfile')->name('user.profile.show');
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