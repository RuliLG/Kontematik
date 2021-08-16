<?php

use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\Api\ToolsController;
use App\Http\Controllers\OauthController;
use App\Http\Controllers\UnsplashController;
use App\Models\User;
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

    Route::get('validate/{token}', function ($token) {
        $user = User::where('api_token', $token)->first();
        return response()->json([
            'success' => $user ? true : false,
        ]);
    });

    Route::middleware('auth:api')->group(function () {
        Route::get('hooks', function () {
            if (auth()->user()->role === 'admin') {
                $hooks = json_decode(file_get_contents(storage_path('app/hooks.json')), true);
                return response()->json($hooks);
            }

            return response()->json(['error' => 'No permissions'], 401);
        });
        Route::get('tools', [ToolsController::class, 'index']);
        Route::get('tools/{tool:slug}', [ToolsController::class, 'show']);

        Route::middleware('subscribed')->group(function () {
            Route::post('{tool:slug}/inference', [ToolsController::class, 'inference']);
            Route::post('{tool:slug}/save', [ToolsController::class, 'save']);
            Route::post('oauth', [OauthController::class, 'perform']);
            Route::get('oauth/{tool:slug}/actions', [OauthController::class, 'getActions']);
            Route::get('unsplash', [UnsplashController::class, 'generate']);
        });
    });
});
