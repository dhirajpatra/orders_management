
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

// route to show the login form
Route::get('/', [
    'as' => 'login',
    'uses' => 'HomeController@showLogin'
]);

// route to show the login form
Route::get('login', [
    'as' => 'login',
    'uses' => 'HomeController@showLogin'
]);

// route to process the login form
Route::post('/', [
    'as' => 'login_post',
    'uses' => 'HomeController@doLogin'
]);

// route to logout
Route::get('logout', [
    'as' => 'logout',
    'uses' => 'HomeController@doLogout'
])->middleware('auth');

// route to order management
Route::get('orders', [
    'as' => 'orders',
    'uses' => 'OrdersController@showOrderForm'
])->middleware('auth');

// route to order management
Route::post('orders_post', [
    'as' => 'orders_post',
    'uses' => 'OrdersController@doOrder'
])->middleware('auth');

// route to order management to show all orders
Route::get('orders_list', [
    'as' => 'orders_list',
    'uses' => 'OrdersController@show'
])->middleware('auth');

// route to order management edit
Route::post('orders_edit', [
    'as' => 'orders_edit',
    'uses' => 'OrdersController@edit'
])->middleware('auth');

// route to order management update
Route::put('orders_update', [
    'as' => 'orders_update',
    'uses' => 'OrdersController@updateOrder'
])->middleware('auth');

// route to order management update
Route::delete('orders_delete', [
    'as' => 'orders_delete',
    'uses' => 'OrdersController@deleteOrder'
])->middleware('auth');

// route to order management details for order search
Route::get('orders_search', [
    'as' => 'orders_search',
    'uses' => 'OrdersController@search'
])->middleware('auth');