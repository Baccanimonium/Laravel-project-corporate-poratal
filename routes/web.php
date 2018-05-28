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

Route::resource('/','IndexController',[
    'only' => ['index'],
    'names'=>[
        'index'=>'home'
    ]
]);
Route::resource('portfolios','PortfolioController',[
                                                    'parameters'=>[
                                                        'portfolios'=>'alias'
                                                    ]
]);
Route::resource('articles', 'ArticlesController',[
                                                    'parameters'=>[
                                                        'articles'=>'alias'
                                                ]
]);
Route::get('articles/cat/{cat_articles?}',['uses'=>'ArticlesController@index','as'=>'articlesCat']);


Route::resource('comment','CommentController',[
    'only' => ['store']
]);
Route::match(['get','post'],'/contacts',['uses'=>'ContactController@index', 'as' => 'contacts']);

Route::get('login',['uses'=>'Auth\LoginController@showLoginForm','as'=>'login']);
Route::post('login',['uses'=>'Auth\LoginController@login','as'=>'login']);
Route::get('/logout','Auth\LoginController@logout');

//Route::get('login','Auth\LoginController@showLoginForm');
//
//Route::post('login','Auth\LoginController@login');

Route::group(['prefix'=>'admin'], function () {
    Route::get('/','Admin\IndexController@index')
    ->name('adminIndex')
    ->middleware('auth');

    Route::resource('adminArticles','Admin\ArticlesController')->middleware('can:VIEW_ARTICLES');
    Route::resource('adminPermissions','Admin\PermissionsController')->middleware('can:VIEW_ARTICLES');
    Route::resource('adminMenus','Admin\MenusController')->middleware('can:VIEW_ARTICLES');
    Route::resource('adminUsers','Admin\MenusController')->middleware('can:VIEW_ARTICLES');
});

////admin
//Route::group(['prefix' => 'admin','middleware'=> 'auth'],function() {
//
//    //admin
//    Route::get('/',['uses' => 'Admin\IndexController@index','as' => 'adminIndex']);
//
//    Route::resource('/articles','Admin\ArticlesController');



