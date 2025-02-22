<?php
// endpoint url, ControllerName @ function
Route::get('/login', 'AuthController@login');
Route::post('/login', 'AuthController@login');
Route::get('/logout', 'AuthController@logout');
Route::get('/', 'DashboardController@index');
Route::get('/dashboard', 'DashboardController@index');
Route::get('/dashboard/detail-queue/{id}', 'DashboardController@detailQueue');

Route::get('/user', 'UserController@index');
Route::get('/user/add', 'UserController@add');
Route::post('/user/insert', 'UserController@insert');
Route::get('/user/edit/{id}', 'UserController@edit');
Route::post('/user/update', 'UserController@update');
Route::post('/user/delete/{id}', 'UserController@delete');

Route::get('/role', 'RoleController@index');
Route::get('/role/add', 'RoleController@add');
Route::post('/role/insert', 'RoleController@insert');
Route::get('/role/edit/{id}', 'RoleController@edit');
Route::post('/role/update', 'RoleController@update');
Route::post('/role/delete/{id}', 'RoleController@delete');

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
Route::post('/customer/delete/{id}', 'CustomerController@delete');

Route::get('/service', 'ServiceController@index');
Route::get('/service/add', 'ServiceController@add');
Route::post('/service/insert', 'ServiceController@insert');
Route::get('/service/edit/{id}', 'ServiceController@edit');
Route::post('/service/update', 'ServiceController@update');
Route::post('/service/delete/{id}', 'ServiceController@delete');

Route::get('/promotion', 'PromotionController@index');
Route::get('/promotion/add', 'PromotionController@add');
Route::post('/promotion/insert', 'PromotionController@insert');
Route::get('/promotion/edit/{id}', 'PromotionController@edit');
Route::post('/promotion/update', 'PromotionController@update');
Route::post('/promotion/delete/{id}', 'PromotionController@delete');

Route::get('/estimate', 'EstimateController@index');
Route::get('/estimate/detail/{id}', 'EstimateController@detail');
Route::post('/estimate/update', 'EstimateController@update');

Route::get('/comment', 'CommentController@index');
Route::get('/comment/detail/{id}', 'CommentController@detail');
Route::post('/comment/update', 'CommentController@update');
Route::post('/comment/delete/{id}', 'CommentController@delete');

Route::get('/finance', 'FinanceController@index');
Route::get('/finance/add', 'FinanceController@add');
Route::post('/finance/insert', 'FinanceController@insert');
Route::get('/finance/edit/{id}', 'FinanceController@edit');
Route::post('/finance/update', 'FinanceController@update');
Route::post('/finance/delete/{id}', 'FinanceController@delete');

Route::get('/stock', 'StockController@index');
Route::get('/stock/add', 'StockController@add');
Route::post('/stock/insert', 'StockController@insert');
Route::get('/stock/edit/{id}', 'StockController@edit');
Route::post('/stock/update', 'StockController@update');
Route::post('/stock/delete/{id}', 'StockController@delete');