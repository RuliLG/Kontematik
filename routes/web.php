<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CopyController;
use App\Http\Controllers\LibraryController;
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

Route::middleware('auth')->group(function () {
    Route::get('/tools', [CopyController::class, 'render'])->name('dashboard');
    Route::get('/tools/{service:slug}', [CopyController::class, 'renderTool'])->name('tool');
    Route::get('/library', [LibraryController::class, 'render'])->name('library');

    Route::middleware('admin')->group(function () {
        Route::get('/admin', [AdminController::class, 'render'])->name('admin');
        Route::get('/admin/service/new', [AdminController::class, 'renderNewService'])->name('admin.new-service');
        Route::get('/admin/service/{service:slug}', [AdminController::class, 'renderEditService'])->name('admin.service');
    });
});

require __DIR__.'/auth.php';
