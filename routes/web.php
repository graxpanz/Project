<?php
// endpoint url, ControllerName @ function
Route::get('/login', 'AuthController@login');
http://localhost:8080/login
Route::post('/login', 'AuthController@login');
Route::get('/', 'DashboardController@index');
Route::get('/dashboard', 'DashboardController@index');
Route::get('/dashboard/detail-queue/{id}', 'DashboardController@detailQueue');

Route::get('/employee', 'EmployeeController@index');
Route::get('/employee/add', 'EmployeeController@add');
Route::post('/employee/insert', 'EmployeeController@insert');
Route::get('/employee/edit/{id}', 'EmployeeController@edit');
Route::post('/employee/update', 'EmployeeController@update');
Route::get('/employee/delete/{id}', 'EmployeeController@delete');

Route::get('/customer', 'CustomerController@index');
Route::get('/customer/add', 'CustomerController@add');
Route::post('/customer/insert', 'CustomerController@insert');
Route::get('/customer/edit/{id}', 'CustomerController@edit');
Route::post('/customer/update', 'CustomerController@update');
Route::get('/customer/update', 'CustomerController@update');