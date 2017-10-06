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

Route::get('/', function () {return view('backend.add_mosque');})->name('add_mosque_form');

Route::post('Add-Mosque', 'MosqueController@saveMosque')->name('add_mosque');
Route::get('subscribe-user', 'SubscribeUserController@index')->name('subscribe_user');
Route::post('subscribe-user', 'SubscribeUserController@saveSubscriber')->name('save_subscriber');
