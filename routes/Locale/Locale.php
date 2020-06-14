<?php

use Illuminate\Support\Facades\Route;

/**
 * Locale Controllers
 */

Route::get('/locale/{locale}', 'LocaleController@swap')->name('swap');
