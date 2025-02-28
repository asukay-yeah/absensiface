<?php

Route::get('/', function () {
    return view('auth.login');
});

Auth::routes();
Route::get('/home', 'HomeController@index')->name('home');

// Data management routes (CRUD)
Route::resource('data', 'DataController');

// Attendance routes
Route::resource('absen', 'UserController');

// Report route
Route::get('/report', 'ReportController@index')->name('report');

// Add a web-accessible route for face data when needed for the attendance kiosk
Route::get('/api/faces', 'FaceRecognitionController@getAllFaces');