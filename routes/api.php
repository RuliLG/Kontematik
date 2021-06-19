<?php

use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\Api\ToolsController;
use App\Http\Controllers\OauthController;
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


Route::prefix('v1')->group(function () {
    Route::post('login', [LoginController::class, 'login']);

    Route::middleware('auth:oauth-api')->group(function () {
        Route::get('tools', [ToolsController::class, 'index']);
        Route::post('{tool:slug}/inference', [ToolsController::class, 'inference']);
        Route::post('oauth', [OauthController::class, 'perform']);
        Route::get('oauth/{tool:slug}/actions', [OauthController::class, 'getActions']);
    });
});
