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