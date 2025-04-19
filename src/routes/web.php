<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthenticatedSessionController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PurchaseController;
use App\Models\Item;
use App\Models\Profile;
use App\Models\Purchase;
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
Route::get('/item/{item}', [ItemController::class, 'detail'])->name('detail');

Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisterController::class, 'create'])->name('register');
    Route::post('/register', [RegisterController::class, 'store']);

    Route::get('/login', [AuthenticationController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticationController::class, 'store']);
});


Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthenticationController::class, 'destroy'])->name('logout');

    Route::get('/sell', [ItemController::class, 'sell'])->name('sell');
    Route::post('/sell', [ItemController::class, 'store']);

    Route::get('/mypage', [ProfileController::class, 'viewProfile'])->name('mypage');

    Route::get('/mypage/profile', [ProfileController::class, 'changeProfile'])->name('changeProfile');
    Route::post('/mypage/profile', [ProfileController::class, 'update'])->name('update');

    Route::post('/item/{item}/like', [LikeController::class, 'toggleLike'])->name('like.toggle');
    Route::post('/item/{item}/comment', [CommentController::class, 'store'])->name('comment');

    Route::get('/purchase/{item}', [PurchaseController::class, 'viewPurchase'])->name('viewPurchase');
    Route::post('/purchase/{item}', [PurchaseController::class, 'Purchase'])->name('purchase');

    Route::get('purchase/address/{item}', [PurchaseController::class, 'viewAddress'])->name('viewAddress');
    Route::post('purchase/address/{item}', [PurchaseController::class, 'mailingAddress'])->name('mailingAddress');
});
