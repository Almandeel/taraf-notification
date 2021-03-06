<?php

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
Route::group(['middleware' => 'auth'], function() {
    // Route::get('/', function () {
    //     return view('index');
    // })->name('home');
    // Route::get('/home', function () {
    //     return view('index');
    // })->name('home');
    
    
    Route::post('status/change', 'StatusController@change')->name('status.change');
    Route::get('vouchers/{voucher}', 'HomeController@voucher')->name('home.voucher');
    Route::get('notifications', 'HomeController@notification');
    
    
    Route::resource('attachments', 'AttachmentController');
    Route::resource('logs', 'LogController');
    
    Route::resource('backups', 'BackupController')->except(['create', 'edit']);
    Route::post('backups/restore', 'BackupController@restore')->name('backups.restore');
    
});
Auth::routes();
//Clear configurations:
// Route::get('/config-clear', function() {
//     $status = Artisan::call('config:clear');
//     return '<h1>Configurations cleared</h1>';
// });

// //Clear cache:
// Route::get('/cache-clear', function() {
//     $status = Artisan::call('cache:clear');
//     return '<h1>Cache cleared</h1>';
// });

// //Clear configuration cache:
// Route::get('/config-cache', function() {
//     $status = Artisan::call('config:Cache');
//     return '<h1>Configurations cache cleared</h1>';
// });