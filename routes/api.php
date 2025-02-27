<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Routing\Router;

/**
 * Public Routes
 */
Route::group([
    'as' => "otp.",
    'middleware' => ['throttle:otp'],
    'prefix' => config('otp.routes.prefix'),
    'namespace' => 'Elysiumrealms\Otp\Http\Controllers',
], function (Router $router) {

    $router->post('/{via}/generate', 'OtpController@generate')
        ->name('generate');

    $router->post('/{via}/validate', 'OtpController@validate')
        ->name('validate');
});
