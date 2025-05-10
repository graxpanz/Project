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

Route::get('/service_type', 'ServiceTypeController@index');
Route::get('/service_type/add', 'ServiceTypeController@add');
Route::post('/service_type/insert', 'ServiceTypeController@insert');
Route::get('/service_type/edit/{id}', 'ServiceTypeController@edit');
Route::post('/service_type/update', 'ServiceTypeController@update');
Route::post('/service_type/delete/{id}', 'ServiceTypeController@delete');

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

Route::get('/booking', 'BookingController@index');
Route::get('/booking/add', 'BookingController@add');
Route::post('/booking/insert', 'BookingController@insert');
Route::get('/booking/edit/{id}', 'BookingController@edit');
Route::post('/booking/update', 'BookingController@update');
Route::post('/booking/delete/{id}', 'BookingController@delete');
Route::post('/booking/transaction/upload', 'TransactionController@upload');

Route::get('/feedback', 'FeedbackController@index');
Route::get('/feedback/add', 'FeedbackController@add');
Route::post('/feedback/insert', 'FeedbackController@insert');
Route::get('/feedback/edit/{id}', 'FeedbackController@edit');
Route::post('/feedback/update', 'FeedbackController@update');
Route::post('/feedback/delete/{id}', 'FeedbackController@delete');

Route::get('/estimate', 'EstimateController@index');
Route::get('/estimate/add', 'EstimateController@add');
Route::post('/estimate/insert', 'EstimateController@insert');
Route::get('/estimate/edit/{id}', 'EstimateController@edit');
Route::post('/estimate/update', 'EstimateController@update');
Route::post('/estimate/delete/{id}', 'EstimateController@delete');
Route::get('/estimate/respond/{id}', 'EstimateController@respond');
Route::post('/estimate/update_response', 'EstimateController@update_response');

Route::get('/work-calendar', 'WorkCalendarController@index');

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