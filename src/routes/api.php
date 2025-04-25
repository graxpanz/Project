<?php
// endpoint url, ControllerName @ function
Route::post('/api/customer/register', 'CustomerController@api_register');
Route::post('/api/customer/login', 'CustomerController@api_login');
Route::post('/api/customer/logout', 'CustomerController@api_logout');
Route::get('/api/customer', 'CustomerController@api_customer');

Route::get('/api/service', 'ServiceController@api_service');
Route::get('/api/service/{id}', 'ServiceController@api_service_by_id');