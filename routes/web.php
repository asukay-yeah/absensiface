<?php

Route::get('/', function () {
    return view('auth.login');
});

Auth::routes();
Route::get('/home', 'HomeController@index')->name('home');


Route::resource('data', 'DataController');
Route::resource('absen', 'UserController');


// Route::get('/data', 'DataController@index')->name('data');
// Route::get('/tambah', 'TambahController@index')->name('tambah');
// routes/web.php
// Route::resource('tambah', 'TambahController');

Route::get('/report', 'ReportController@index')->name('report');