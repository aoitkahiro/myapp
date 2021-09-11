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
    Route::get('course/profile', 'Admin\CourseController@profile');
    Route::post('course/profile', 'Admin\CourseController@profileUpdate');
    //Route::get('course/index', 'Admin\CourseController@index');
    Route::get('course/index', 'Admin\CourseController@index')->middleware('auth'); //->middleware('auth')はログイン用のコード
    Route::get('course/select', 'Admin\CourseController@select');
    Route::get('course/wordbook', 'Admin\CourseController@wordbook');
    Route::post('course/wordbook', 'Admin\StatusController@store')->middleware('auth');
    Route::get('course/write', 'Admin\CourseController@write');
    Route::post('course/write', 'Admin\CourseController@update');
    
    //以下、新たに単語帳を作るRouting（createアクション）
    Route::get('course/create', 'Admin\CourseController@create');
    /*Route::get('course/csv', 'Admin\CourseController@csv'); //csv表示
    Route::post('course/csv', 'Admin\CourseController@upload_regist'); //csv取込み-登録*/
    Route::get('course/csv2', 'Admin\CourseController@csv2'); //csv表示
    Route::post('course/csv2', 'Admin\CourseController@inportCsv'); //csv取込み-登録
    
    //CSS練習用のページ（削除予定）
    Route::get('course/practice', 'Admin\CourseController@practice');
    //JavaScriptで作ってみたページ
    Route::get('course/quiz', 'Admin\CourseController@quiz');
    Route::get('course/quiz2', 'Admin\CourseController@quiz2');
    
});
Auth::routes();
    Route::get('/home', 'HomeController@index')->name('home');
