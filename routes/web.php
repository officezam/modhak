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

//Route::get('/', function () {return view('backend.add_mosque');})->name('add_mosque_form');
Route::get('/',  'MosqueController@mosqueRecord');
Route::get('add-time', function () {return view('backend.add_time');})->name('add_time_form');
Route::get('update-time/{namaz_id}', 'MosqueController@getNamazTime')->name('updae-time');

Route::post('Add-Mosque', 'MosqueController@saveMosque')->name('add_mosque');
Route::get('Mosque', 'MosqueController@mosqueRecord')->name('mosque_record');
Route::post('save-namaz-time', 'MosqueController@saveNamazTime');
Route::get('subscribe-user', 'SubscribeUserController@index')->name('subscribe_user');

Route::post('subscribe-user', 'SubscribeUserController@saveSubscriber')->name('save_subscriber');
Route::get('sms-sending', 'SmsSendController@index')->name('send_sms');
Route::get('sms-sending', 'SmsSendController@smsSending')->name('sending_sms');
