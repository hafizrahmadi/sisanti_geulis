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

Route::get('/', function () {
    // return view('welcome');
	return view('content');
});

Route::get('/login', function () {
    // return view('welcome');
	return view('login');
});

// Route::get('/aaa',function(){
// 	return view('content');
// });
