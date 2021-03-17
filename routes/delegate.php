<?php

Route::group(['namespace' => 'Delegate'], function() {
    Route::get('/', 'HomeController@index')->name('delegate.dashboard');

    // Login
    Route::get('login', 'Auth\LoginController@showLoginForm')->name('delegate.login');
    Route::post('login', 'Auth\LoginController@login');
    Route::post('logout', 'Auth\LoginController@logout')->name('delegate.logout');

    // Register
    Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('delegate.register');
    Route::post('register', 'Auth\RegisterController@register');

    // Passwords
    Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('delegate.password.email');
    Route::post('password/reset', 'Auth\ResetPasswordController@reset');
    Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('delegate.password.request');
    Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('delegate.password.reset');

    // Must verify email
    Route::get('email/resend','Auth\VerificationController@resend')->name('delegate.verification.resend');
    Route::get('email/verify','Auth\VerificationController@show')->name('delegate.verification.notice');
    Route::get('email/verify/{id}/{hash}','Auth\VerificationController@verify')->name('delegate.verification.verify');
});
