<?php

use App\Http\Controllers\CopyController;
use App\Http\Controllers\LibraryController;
use App\Http\Controllers\ProfileController;
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
    return Auth::check() ? redirect(route('dashboard')) : redirect(route('login'));
});

Route::middleware('auth', 'verified')->group(function () {
    Route::get('/tools', [CopyController::class, 'render'])->name('dashboard');
    Route::get('/tools/{service:slug}', [CopyController::class, 'renderTool'])->name('tool');
    Route::get('/account', [ProfileController::class, 'render'])->name('profile');
    Route::get('/account/password', [ProfileController::class, 'renderPassword'])->name('profile.password');
    Route::get('/library', [LibraryController::class, 'render'])->name('library');
});

require __DIR__.'/auth.php';
