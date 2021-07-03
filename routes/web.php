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

Route::get('faq', 'FaqController@create');

Route::get('about', 'AboutController@create');


Route::get('/contact', [
    'uses' => 'ContactController@create'
]);
Route::post('/contact', [
    'uses' => 'ContactController@store',
    'as' => 'contact.store'
]);

//Products
Route::get('products/{id_product}', 'ProductController@show')->where('id_product', '[0-9]+')->name('product'); //R301
Route::get('products/create/', 'ProductController@insertform'); //R302
Route::get('products/{id_product}/edit/', 'ProductController@edit')->where('id_product', '[0-9]+'); //R303
Route::post('products/create/', 'ProductController@insertform'); //R304
Route::put('products/{id_product}/edit/', 'ProductController@update')->where('id_product', '[0-9]+'); //R305
//Route::get('products/{category}/', 'ProductController@category')->where('category', '[a-zA-Z_-]+'); //R306
Route::get('products/page/{page}', 'ProductController@page')->where('page', '[1-9][0-9]*');
Route::put('/products/{id_product}/cancel/', 'ProductController@cancel')->where('id_product', '[0-9]+'); //R311
Route::post('/products/search', 'ProductController@search')->name('product.search'); //R316
Route::get('/api/products/search', 'ProductController@apiSearch')->name('product.apiSearch');
//Route::get('/products/filter/', 'ProductController@search'); //R313
Route::put('/products/{id_product}/evaluate/', 'ProductController@eval')->where('id_product', '[0-9]+'); //R314
Route::get('/products', 'ProductController@list')->name('products');
Route::put('/products', 'ProductController@list')->name('products_put');  //   //PUT
Route::get('/products/filter', 'ProductController@listFilter');
Route::get('/products/recent', 'ProductController@recent');
Route::get('/products/ending', 'ProductController@ending');
Route::get('/products/expensive', 'ProductController@expensive');
Route::post('/products/report/{id_product}', 'ProductController@report')->where('id_product', '[0-9]+'); //R315
Route::post('/products/comments/report/{id_comment}', 'ProductController@reportComment')->where('id_comment', '[0-9]+'); //R308
Route::get('/products/categories', 'ProductController@getEnum');
Route::get('/products/create', 'ProductController@create');
Route::post('/products/create', 'ProductController@store');


//Users
Route::get('users/{id_user}', 'UserController@show')->where('id_user', '[0-9]+');
Route::get('users/edit', 'UserController@edit');
Route::get('users/{id_user}/notifications', 'UserController@notifications')->where('id_user', '[0-9]+');
Route::get('users/{id_user}/history', 'UserController@history')->where('id_user', '[0-9]+');
Route::get('users/{id_user}/report', 'UserController@report')->where('id_user', '[0-9]+');
Route::put('users/cancel', 'UserController@cancel');
Route::post('/users/edit', 'UserController@store');
Route::get('/user/{id}', 'UserController@show')->where('id_product', '[0-9]+')->name('showuser');

//API calls
Route::get('/api/products', 'ProductController@searchOrFilter');
Route::get('/api/products/{id}/comments/{id_comment?}', 'ProductController@getComments')->where(['id' => '[0-9]+', 'id_comment' => '[0-9]+']);
Route::get('/api/products/{id}/bids/', 'ProductController@getBids')->where('id', '[0-9]+');
Route::post('/api/products/{id}/comments/', 'CommentController@store')->where('id', '[0-9]+');
Route::post('/api/products/{id}/bids/', 'BiddingController@store')->where('id', '[0-9]+');
Route::post('/api/products/{id_product}/buy', 'TransactionController@store')->where('id', '[0-9]+');
Route::delete('/api/products/{id}/comments/{id_comment}', 'CommentController@destroy')->where(['id' => '[0-9]+', 'id_comment' => '[0-9]+']);
Route::put('/api/products/{id}/comments/{id_comment}', 'CommentController@putLike')->where(['id' => '[0-9]+', 'id_comment' => '[0-9]+']);

Route::get('/api/members', 'UserController@searchMembers');
Route::get('/api/city/{postal}', 'UserController@getCity')->where('postal', '[1-9]\d{3}');
Route::get('/api/users/{pattern}', 'UserController@search'); //FTS

//Notifications
Route::get('/api/notifications/user/{id}', 'NotificationController@getNotifications')->where(['id' => '[0-9]+']);;

//Admin
Route::get('/admin', 'AdminController@home')->name('admin.home')->middleware('auth:admin');
Route::get('/admin/filter', 'AdminController@homeFilter')->name('admin.home.filter');
Route::get('/admin/login', 'Auth\AdminLoginController@showLoginForm')->name('admin.login');
Route::post('/admin/login', 'Auth\AdminLoginController@login')->name('admin.post');
Route::get('admin/users', 'AdminController@usersSearch')->name('admin-users');

Route::get('admin/history', 'AdminController@history');
Route::get('admin/add', 'AdminController@register');
Route::post('admin/add', 'AdminController@create');
Route::get('admin/search', 'AdminController@search')->name('admin.search');
Route::get('report/{id}', 'AdminController@show')->where('id', '[0-9]+');
Route::put('report/{id}', 'AdminController@updateReportStatus')->where('id', '[0-9]+');
Route::put('/admin/bans/member/{id}', 'AdminController@ban')->where('id', '[0-9]+');

Route::get('/api/admin/search', 'AdminController@apiSearch');
Route::delete('/api/admin/bans/user', 'AdminController@destroy');

Route::put('/admin/bans/product/{id_product}', 'ProductController@remove')->where('id_product', '[0-9]+');




// Authentication
Route::get('/login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('/login', 'Auth\LoginController@login');
Route::get('/logout', 'Auth\LoginController@logout')->name('logout');
Route::get('/register', 'Auth\RegisterController@showRegistrationForm')->name('register');
Route::post('/register', 'Auth\RegisterController@register');
Route::get('/recovery', 'Auth\LoginController@recovery')->name('recovery');


// Errors
Route::view('/400', 'errors.400', ['product'=>'0']);
Route::view('/403', 'errors.403');
Route::view('/404', 'errors.404', ['product'=>'0'])->name('error404');
Route::view('/422', 'errors.422', ['product'=>'0', 'status'=>'OK']);
Route::view('/500', 'errors.500');



Route::get('/', 'ProductController@index')->name('index');
Route::get('/build', 'Controller@build');
Route::get('/log', 'Controller@log');