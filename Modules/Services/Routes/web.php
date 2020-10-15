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


Route::middleware('auth')->prefix('services')->group(function () {

  Route::get('/', 'ServicesController@index')->name('services.dashboard');
  Route::post('returns/{cv}', 'CvController@returns')->name('returns.store');
  Route::post('servicesmarketers/credit', 'MarketerController@credit')->name('servicesmarketers.credit');
  Route::get('get/cvs/{country}/{profession}', 'ServicesController@cvs')->name('get.cvs');
  Route::get('get/cv/{cv}', 'ServicesController@getCv')->name('get.cv');
  Route::get('get/cvs/{customer}', 'ServicesController@customerCvs');
  Route::put('cvs/pulls/{pull}', 'CvController@pull')->name('cvs.pulls.update');

  Route::resource('/customers', 'CustomerController');
  Route::resource('/complaints', 'ComplaintController');
  Route::resource('/contracts', 'ContractController');

  Route::get('customer/add/contract/{customer}', 'CustomerController@addContract')->name('customers.addcontract');
  Route::post('/business/contract', 'ServicesController@business')->name('contracts.business');

  Route::resources([
    'servicescontracts' => 'ContractController',
    'servicesmarketers' => 'MarketerController',
    'servicescvs'       => 'CvController',
  ]);
});
