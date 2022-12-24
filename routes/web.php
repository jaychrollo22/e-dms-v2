<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return redirect('/login');
});

Route::group(['prefix' => 'access-requests'], function () {
    Route::get('/create', 'AccessRequestController@create');
    Route::post('/store', 'AccessRequestController@store');
});

Route::get('/companies-options', 'CompanyController@companies');
Route::get('/departments-options', 'DepartmentController@departments');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/home-user', 'HomeController@userIndex')->name('home-user');
Route::get('/dashboard-data', 'HomeController@dashboardData');
Route::get('/user-dashboard-data', 'HomeController@userDashboardData');

//Document Requests
Route::get('/document-requests', 'DocumentRequestController@index')->name('document-requests');
Route::group(['prefix' => 'document-requests'], function () {
    Route::get('/all', 'DocumentRequestController@indexData');
    Route::get('/create', 'DocumentRequestController@create');
    Route::post('/store', 'DocumentRequestController@store');
    Route::post('/update-approval', 'DocumentRequestController@updateApproval');
});

Route::get('/user-document-requests', 'DocumentRequestController@userIndex')->name('user-document-requests');
Route::group(['prefix' => 'user-document-requests'], function () {
    Route::get('/all', 'DocumentRequestController@userIndexData');
    Route::post('/update', 'DocumentRequestController@update');
});

//Document Copy Requests
Route::get('/document-copy-requests', 'DocumentCopyRequestController@index')->name('document-copy-requests');
Route::group(['prefix' => 'document-copy-requests'], function () {
    Route::get('/all', 'DocumentCopyRequestController@indexData');
    Route::get('/create', 'DocumentCopyRequestController@create');
    Route::post('/store', 'DocumentCopyRequestController@store');
    Route::post('/update-approval', 'DocumentCopyRequestController@updateApproval');
});

Route::get('/user-document-copy-requests', 'DocumentCopyRequestController@userIndex')->name('user-document-copy-requests');
Route::group(['prefix' => 'user-document-copy-requests'], function () {
    Route::get('/all', 'DocumentCopyRequestController@userIndexData');
    Route::post('/update', 'DocumentCopyRequestController@update');
});

//Document Uploads
Route::get('/document-uploads', 'DocumentUploadController@index')->name('document-uploads');
Route::get('/document-uploads-request-copy-options', 'DocumentUploadController@documentUploadRequestCopyOptions');
Route::get('/document-uploads-request-options', 'DocumentUploadController@documentUploadRequestOptions');
Route::get('/document-uploads-view-signed-copy', 'DocumentUploadController@documentUploadSignedCopy');
Route::get('/document-uploads-download-signed-copy', 'DocumentUploadController@downloadDocumentUploadSignedCopy');
Route::group(['prefix' => 'document-uploads'], function () {
    Route::get('/all', 'DocumentUploadController@indexData');
    Route::post('/store', 'DocumentUploadController@store');
    Route::post('/store-revision', 'DocumentUploadController@storeRevision');
    Route::get('/get-users', 'DocumentUploadController@getUsers');
    Route::post('/store-user', 'DocumentUploadController@storeUser');
    Route::post('/remove-user', 'DocumentUploadController@removeUser');
    Route::post('/allow-print-user', 'DocumentUploadController@allowPrintUser');
    Route::post('/allow-download-user', 'DocumentUploadController@allowDownloadUser');
    Route::post('/delete-user', 'DocumentUploadController@deleteUser');
    Route::post('/update', 'DocumentUploadController@update');
    Route::post('/update-approval', 'DocumentUploadController@updateApproval');
    Route::post('/save-document-upload-user-print', 'DocumentUploadController@saveDocumentUploadUserPrint');
    Route::post('/save-document-upload-user-download', 'DocumentUploadController@saveDocumentUploadUserDownload');
});

Route::get('/user-document-uploads', 'DocumentUploadController@userIndex')->name('user-document-uploads');
Route::group(['prefix' => 'user-document-uploads'], function () {   
    Route::get('/all', 'DocumentUploadController@userIndexData');
    Route::post('/acknowledge-document', 'DocumentUploadController@userAcknowledgeDocument');
});

//Access Requests
Route::get('/access-requests', 'AccessRequestController@index')->name('access-requests');
Route::group(['prefix' => 'access-requests'], function () {
    Route::get('/all', 'AccessRequestController@indexData');
    Route::post('/update', 'AccessRequestController@update');
});


//Settings
Route::get('/settings', 'SettingController@index')->name('settings');
Route::get('/settings-data', 'SettingController@settingsData');
Route::post('/settings-store', 'SettingController@store');

//Companies
Route::get('/companies', 'CompanyController@index')->name('companies');
Route::group(['prefix' => 'companies'], function () {
    Route::get('/all', 'CompanyController@indexData');
    Route::post('/store', 'CompanyController@store');
    Route::post('/update', 'CompanyController@update');
});

//Departments
Route::get('/departments', 'DepartmentController@index')->name('departments');
Route::group(['prefix' => 'departments'], function () {
    Route::get('/all', 'DepartmentController@indexData');
    Route::post('/store', 'DepartmentController@store');
    Route::post('/update', 'DepartmentController@update');
});

//Roles
Route::get('/roles', 'RoleController@index')->name('roles');
Route::get('/roles-options', 'RoleController@roles');
Route::group(['prefix' => 'roles'], function () {
    Route::get('/all', 'RoleController@indexData');
    Route::post('/store', 'RoleController@store');
    Route::post('/update', 'RoleController@update');
});


//Users
Route::get('/users', 'UserController@index')->name('users');
Route::get('/user-options', 'UserController@userOptions');
Route::get('/immediate-heads', 'UserController@immediateHeads');
Route::group(['prefix' => 'users'], function () {
    Route::get('/all', 'UserController@indexData');
    Route::post('/store', 'UserController@store');
    Route::post('/update', 'UserController@update');
    Route::post('/change-password', 'UserController@changePassword');
});
Route::get('/user-profile', 'UserController@userProfile')->name('user-profile');


//Document Categories
Route::get('/document-categories', 'DocumentCategoryController@index')->name('document-categories');
Route::get('/document-category-options', 'DocumentCategoryController@document_categories');
Route::group(['prefix' => 'document-categories'], function () {
    Route::get('/all', 'DocumentCategoryController@indexData');
    Route::post('/store', 'DocumentCategoryController@store');
    Route::post('/update', 'DocumentCategoryController@update');
});

//Tags
Route::get('/tags', 'TagController@index')->name('tags');
Route::get('/tag-options', 'TagController@tags');
Route::group(['prefix' => 'tags'], function () {
    Route::get('/all', 'TagController@indexData');
    Route::post('/store', 'TagController@store');
    Route::post('/update', 'TagController@update');
});

