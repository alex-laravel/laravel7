<?php

use Illuminate\Support\Facades\Route;

/**
 * Auth Controllers
 */

if (config('auth.enabled_registration')) {
    Route::get('register', 'RegisterController@showRegistrationForm')->name('register');
    Route::post('register', 'RegisterController@register')->name('register.request');
}

Route::get('login', 'LoginController@showLoginForm')->name('login');
Route::post('login', 'LoginController@login')->name('login.request');
Route::post('logout', 'LoginController@logout')->name('logout');

Route::get('password/reset', 'ForgotPasswordController@showLinkRequestForm')->name('password.request');
Route::post('password/email', 'ForgotPasswordController@sendResetLinkEmail')->name('password.email');
Route::get('password/reset/{token}', 'ResetPasswordController@showResetForm')->name('password.reset');
Route::post('password/reset', 'ResetPasswordController@reset')->name('password.update');

Route::get('password/confirm', 'ConfirmPasswordController@showConfirmForm')->name('password.confirm');
Route::post('password/confirm', 'ConfirmPasswordController@confirm')->name('password.confirm.update');

Route::get('email/verify', 'VerificationController@show')->name('verification.notice');
Route::get('email/verify/{id}/{hash}', 'VerificationController@verify')->name('verification.verify');
Route::post('email/resend', 'VerificationController@resend')->name('verification.resend');
