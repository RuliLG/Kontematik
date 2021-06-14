<?php

use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\Api\ToolsController;
use App\Http\Controllers\IntelligenceController;
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


Route::prefix('v1')->group(function () {
    Route::post('login', [LoginController::class, 'login']);
    Route::middleware('auth:api')->group(function () {
        Route::get('tools', [ToolsController::class, 'index']);
        Route::post('{tool:slug}/inference', [ToolsController::class, 'inference']);
        // Route::post('/ai/language-detection', [IntelligenceController::class, 'detectLanguage']);
    });
});
