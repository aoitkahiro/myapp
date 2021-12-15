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
    return view('welcome');
});

Route::group(['prefix' => 'admin'], function() {
    Route::get('course/start', 'Admin\CourseController@start');
    Route::get('course/profile', 'Admin\CourseController@profile')->middleware('auth');
    Route::get('course/upImage', 'Admin\CourseController@upImage')->middleware('auth');
    Route::post('course/profile', 'Admin\CourseController@profileUpdate')->middleware('auth');
    Route::get('course/index', 'Admin\CourseController@index')->middleware('auth');
    Route::get('course/select', 'Admin\CourseController@select')->middleware('auth');
    Route::get('course/wordbook', 'Admin\CourseController@wordbook')->middleware('auth');
    Route::post('course/wordbook', 'Admin\StatusController@store')->middleware('auth');
    Route::post('course/wordbook/levelChange', 'Admin\StatusController@levelChange')->middleware('auth');
    Route::post('course/wordbook/changeIsImageDisplayed', 'Admin\StatusController@changeIsImageDisplayed')->middleware('auth');
    Route::get('course/write', 'Admin\CourseController@write')->middleware('auth');
    Route::post('course/write', 'Admin\CourseController@update')->middleware('auth');
    Route::get('course/reward', 'Admin\CourseController@reward')->middleware('auth');
    Route::get('course/create', 'Admin\CourseController@create')->middleware('auth');
    Route::get('course/csv2', 'Admin\CourseController@csv2')->middleware('auth'); //csv表示
    Route::post('course/csv2', 'Admin\CourseController@inportCsv')->middleware('auth'); //csv取込登録み-
    Route::get('course/quiz', 'Admin\CourseController@quiz')->middleware('auth');
    Route::post('course/quiz', 'Admin\CourseController@PostQuizTime')->middleware('auth');
    Route::get('course/quiz2', 'Admin\CourseController@quiz2')->middleware('auth');
    Route::get('course/ranking', 'Admin\CourseController@ranking')->middleware('auth');
    Route::get('course/showResult', 'Admin\CourseController@showResult')->middleware('auth');
    
});
Auth::routes();
    Route::get('/home', 'HomeController@index')->name('home');
