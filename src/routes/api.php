<?php
// endpoint url, ControllerName @ function
Route::get('/api/user/employee', 'UserController@api_employee');

Route::post('/api/customer/register', 'CustomerController@api_register');
Route::post('/api/customer/login', 'CustomerController@api_login');
Route::post('/api/customer/logout', 'CustomerController@api_logout');
Route::get('/api/customer', 'CustomerController@api_customer');

Route::get('/api/service', 'ServiceController@api_service');
Route::get('/api/service/{id}', 'ServiceController@api_service_by_id');

Route::get('/api/promotion', 'PromotionController@api_promotion');
Route::post('/api/promotion/redeem', 'PromotionController@api_redeem');

Route::post('/api/booking/add', 'BookingController@api_booking_add');
Route::get('/api/booking/customer', 'BookingController@api_booking_by_customer'); // can add ?status=pending also ['pending', 'confirm', 'complete', 'cancel']
Route::post('/api/booking/cancel', 'BookingController@api_booking_cancel');

Route::get('/api/feedback', 'FeedbackController@api_feedback');
Route::post('/api/feedback/add', 'FeedbackController@api_feedback_add');