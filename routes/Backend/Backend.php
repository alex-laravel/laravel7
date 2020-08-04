<?php

use Illuminate\Support\Facades\Route;

/**
 * Backend Controllers
 */

Route::get('/dashboard', 'DashboardController@index')->name('index');
