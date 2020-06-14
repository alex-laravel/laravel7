<?php

use Illuminate\Support\Facades\Route;

/**
 * Backend Controllers
 */

Route::get('/', 'DashboardController@index')->name('index');
