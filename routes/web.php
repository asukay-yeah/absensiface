<?php

use Illuminate\Http\Request;

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

// Add this route to check if an employee has already clocked in
Route::get('/check-attendance', function (Request $request) {
    $nama = $request->query('nama');
    $nip = $request->query('nip');
    
    // Find the employee
    $pegawai = App\Pegawai::where('nama', $nama)->where('nip', $nip)->first();
    
    if (!$pegawai) {
        return response()->json(['can_clock_out' => false]);
    }
    
    // Check if there's an attendance record for today
    $today = \Carbon\Carbon::today()->toDateString();
    $hasClockIn = App\Kehadiran::where('pegawai_id', $pegawai->id)
        ->where('tanggal', $today)
        ->whereNotNull('jam_masuk')
        ->exists();
    
    return response()->json(['can_clock_out' => $hasClockIn]);
});


Route::get('/get-security-shift/{id}', 'DataController@getSecurityShift');