<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
Route::post('login', 'Api\AuthController@login');
Route::post('register', 'Api\AuthController@register');

Route::middleware('auth:api')->group(function () {
    // our routes to be protected will go in here
    Route::post('ApproveLoan', 'Api\loantablecontroller@ApproveLoan');
    Route::post('PayDuePayment', 'Api\loantablecontroller@PayDuePayment');
    Route::post('CheckPaymentStatus', 'Api\loantablecontroller@CheckPaymentStatus');
    
    
});
