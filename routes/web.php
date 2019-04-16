<?php

use Symanticreative\Themer\Page;
use Illuminate\Support\Facades\Request;

$accountController = '\Symanticreative\Themer\Http\Controllers\AccountController';
$searchController = '\Symanticreative\Themer\Http\Controllers\SearchController';

/**
 * Authentication
 */
Route::group(['middleware' => ['web']], function () use ($accountController) {
    Route::group(['namespace' => 'App\Http\Controllers'], function () {
        Auth::routes();
    });

    Route::group(['middleware' => 'auth', 'as' => 'voyager-frontend.account'], function () use ($accountController) {
        Route::get('/account', "$accountController@index");
        Route::post('/account', "$accountController@updateAccount");

        /**
         * User impersonation
         */
        Route::get('/admin/users/impersonate/{userId}', "$accountController@impersonateUser")
            ->name('.impersonate')
            ->middleware(['web', 'admin.user']);

        Route::post('/admin/users/impersonate/{originalId}', "$accountController@impersonateUser")
            ->name('.impersonate')
            ->middleware(['web']);
    });
});

Route::group([
    'as' => 'voyager-frontend.pages.',
    'prefix' => 'admin/pages/',
    'middleware' => ['web', 'admin.user'],
    'namespace' => '\Symanticreative\Themer\Http\Controllers'
], function () {
    Route::post('layout/{id?}', ['uses' => "PageController@changeLayout", 'as' => 'layout']);
});

/**
 * Let's get some search going
 */
Route::get('/search', "$searchController@index")
    ->middleware(['web'])
    ->name('voyager-frontend.search');
