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

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');



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
Route::group(['prefix' => 'roles'], function () {
    Route::get('/all', 'RoleController@indexData');
    Route::post('/store', 'RoleController@store');
    Route::post('/update', 'RoleController@update');
});

