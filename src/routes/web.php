<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthenticatedSessionController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\ProfileController;
use App\Models\Item;
use App\Models\Profile;
use Illuminate\Support\Facades\Auth;

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

Route::get('/', [ItemController::class, 'index'])->name('home');
Route::get('/item/{item_id}', [ItemController::class, 'detail'])->name('detail');

Route::post('register', [RegisterController::class, 'store']);

Route::post('login', [AuthenticationController::class, 'store']);
Route::post('logout', [AuthenticationController::class, 'destroy'])->name('logout');

Route::get('/search', [ItemController::class, 'search'])->name('search');
Route::get('/detail', [ItemController::class, 'detail'])->name('detail');

Route::middleware('auth')->group(function () {
    Route::get('/sell', [ItemController::class, 'sell'])->name('sell');
    Route::post('/sell', [ItemController::class, 'store']);
    Route::get('/mypage', [ProfileController::class, 'viewProfile'])->name('mypage');
    Route::get('/mypage/profile', [ProfileController::class, 'changeProfile'])->name('changeProfile');
    Route::post('/mypage/profile', [ProfileController::class, 'update'])->name('update');
});
