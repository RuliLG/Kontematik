<?php

use App\Http\Controllers\CopyController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

header('X-Robots-Tag: noindex, nofollow');

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [CopyController::class, 'render'])->name('dashboard');
    Route::get('/dashboard/{service:slug}', [CopyController::class, 'renderTool'])->name('tool');
});

require __DIR__.'/auth.php';
