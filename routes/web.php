<?php

use Illuminate\Support\Facades\Route;

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

/*
 * Auth Routes
 */
Route::group(['namespace' => 'Auth', 'as' => 'auth.'], function () {
    includeRouteFiles(__DIR__.'/Auth/');
});

/*
 * Backend Routes
 */
Route::group(['namespace' => 'Backend', 'as' => 'backend.', 'prefix' => 'admin'], function () {
    includeRouteFiles(__DIR__.'/Backend/');
});

/*
 * Frontend Routes
 */
Route::group(['namespace' => 'Frontend', 'as' => 'frontend.'], function () {
    includeRouteFiles(__DIR__.'/Frontend/');
});

/*
 * Locale Routes
 */
Route::group(['namespace' => 'Locale', 'as' => 'locale.'], function () {
    includeRouteFiles(__DIR__.'/Locale/');
});
