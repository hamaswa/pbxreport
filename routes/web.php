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
 
Auth::routes();

Route::get('/', function () {
    return view('welcome');
});

Route::group(['prefix' => 'downloads'], function() {
  //Route::resource('/','downloadController');
  //Route::post('/','downloadController@index');
  Route::get('/daily','downloadController@dailydownload');
  Route::get('/weekly','downloadController@weeklydownload');
  Route::get('/sevendays','downloadController@sevendaysdownload');
  Route::get('/export','downloadController@export');

});

Route::group(['prefix' => 'cms', 'middleware' => 'auth'], function() {
    Route::resource('/', 'Cms\HomeController');
	Route::get('/dstats/', 'Cms\HomeController@dashboardStats');
	
	Route::get('/iouserreport', 'Cms\ReportsController@ioUserReport');
	Route::get('/iocallreport', 'Cms\ReportsController@ioCallReport');

    Route::get('/iuserreport', 'Cms\ReportsController@iUserReport');
	Route::get('/ouserreport', 'Cms\ReportsController@oUserReport');
	
	Route::get('/billreport', 'Cms\ReportsController@billReport');
	
	Route::get('/realtime', 'Cms\ReportsController@showRealTimeReport');
	Route::get('/realtime/stats', 'Cms\ReportsController@realTimeReport');

    Route::get('/queuestats', 'Cms\ReportsController@showQueueStatsReport');

    Route::get('/distribution', 'Cms\DistributionController@index');
    Route::post('/distribution', 'Cms\DistributionController@distribution');
    Route::post('/subdata', 'Cms\DistributionController@distributionSubData');

    Route::get('/queuestats/stats', 'Cms\ReportsController@queueStatsReport');

    Route::post('/queuereport', 'Cms\ReportsController@queueReport');
    Route::get('/queuereport', 'Cms\ReportsController@showQueueReport');
    Route::get('/queuereport/stats', 'Cms\ReportsController@queueReport');

	Route::post('/changepassword', 'Cms\HomeController@resetPassword');
	Route::get('/changepassword', 'Cms\HomeController@showChangePassword');
});

Route::group(['prefix' => 'admin'], function () {
  Route::get('/', 'AdminAuth\LoginController@showLoginForm');
  Route::get('/login', 'AdminAuth\LoginController@showLoginForm');
  Route::post('/login', 'AdminAuth\LoginController@login');
    Route::post('/logout', 'AdminAuth\LoginController@logout');
    Route::get('/logout', 'AdminAuth\LoginController@logout');

  Route::get('/register', 'AdminAuth\RegisterController@showRegistrationForm');
  Route::post('/register', 'AdminAuth\RegisterController@register');

  Route::post('/password/email', 'AdminAuth\ForgotPasswordController@sendResetLinkEmail');
  Route::post('/password/reset', 'AdminAuth\ResetPasswordController@reset');
  Route::get('/password/reset', 'AdminAuth\ForgotPasswordController@showLinkRequestForm');
  Route::get('/password/reset/{token}', 'AdminAuth\ResetPasswordController@showResetForm');
  
  
});

Route::group(['prefix' => 'admin', 'middleware' => 'admin'], function() {
	Route::resource('extensions', 'Admin\ExtensionController');
	Route::resource('nusers', 'Admin\UserController');
	
	Route::post('/changepassword', 'Admin\HomeController@resetPassword');
  	Route::get('/changepassword', 'Admin\HomeController@showChangePassword');
	Route::post('/getextension', 'Admin\ExtensionController@getExt');
	Route::post('/addextension', 'Admin\ExtensionController@addExt');
	Route::post('/deleteextension', 'Admin\ExtensionController@deleteExt');
});