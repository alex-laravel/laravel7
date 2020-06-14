<?php

use Illuminate\Support\Facades\Route;

/**
 * Frontend Controllers
 */

Route::get('/', 'HomeController@index')->name('index');
