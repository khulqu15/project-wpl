<?php

use App\Http\Controllers\Web\Information\InformationController;
use App\Http\Controllers\Web\Monitoring\CategoryController;
use App\Http\Controllers\Web\Monitoring\MonitoringController;
use App\Http\Controllers\Web\Monitoring\ObjectController;
use App\Http\Controllers\Web\PageController;
use App\Http\Controllers\Web\Team\TeamController;
use App\Http\Controllers\Web\User\UserController as UserUserController;
use App\Http\Controllers\Web\UserController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

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

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
})->name('home');

Route::post('/app/login', [UserController::class, 'login'])->name('app.login');
Route::middleware(['auth:sanctum', 'verified'])->group(function() {
    Route::post('/logout', [UserController::class, 'logout'])->name('logout');
    Route::get('/verify', [UserController::class, 'indexVerification'])->name('index.verification');
    Route::post('/verify', [UserController::class, 'verification'])->name('app.verification');
    Route::namespace('App')->prefix('app')->name('app.')->group(function() {
        Route::resource('/monitoring/category', CategoryController::class);
        Route::resource('/monitoring/object', ObjectController::class);
        Route::resource('/monitoring', MonitoringController::class);
        Route::resource('/information', InformationController::class);
        Route::resource('/user', UserUserController::class);
        Route::resource('/team', TeamController::class);
    });
});

Route::get('under/construction', [PageController::class, 'index'])->name('under.construction');

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->name('dashboard');
