<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware(['localization'])->group(function () {
    // Authentification
    Route::post('/register', 'Auth\RegisterController@register')->name('register');
    Route::get('/register/confirm', 'Auth\RegisterConfirmationController')->name('register.confirm');
    Route::post('/password/email', 'Auth\ForgotPasswordController')->name('password.email');
    Route::post('/password/reset', 'Auth\ResetPasswordController')->name('password.reset');

    // Categories
    Route::get('/categories', 'CategoryController@index')->name('categories');
    Route::get('/category/{category}/items', 'CategoryController@items')->name('category.items');

    // Items
    Route::get('/items', 'ItemController@index')->name('items');
    Route::get('/item/{item}', 'ItemController@show')->name('item');
    Route::post('/item/store', 'ItemController@store')->name('item.store');
    Route::patch('/item/{item}/update', 'ItemController@update')->name('item.update');
    Route::delete('/item/{item}/delete', 'ItemController@delete')->name('item.delete');

    // Users
    Route::get('/user/{user}', 'UserController@show')->name('user');
    Route::get('/user/{user}/items', 'UserController@showWithItems')->name('user.items');

    // Bookmarks
    Route::get('/bookmarks', 'BookmarkController@index')->name('bookmarks');
    Route::get('/bookmark/{item}/create', 'BookmarkController@create')->name('bookmark.create');
    Route::delete('/bookmark/{item_softdelete}/delete', 'BookmarkController@delete')->name('bookmark.delete');
});
