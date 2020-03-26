<?php
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

Route::group([ 'namespace' => 'Api'], function(){

    Route::group(['prefix' => 'auth'], function () {
        Route::post('login', 'AuthController@login')->name('login');
        Route::post('register', 'AuthController@register')->name('register');

        Route::group(['middleware' => 'auth:api', 'swfix'], function () {
            Route::get('logout', 'AuthController@logout')->name('logout');
        });
    });

    Route::apiResource('products', 'ProductController');

});
