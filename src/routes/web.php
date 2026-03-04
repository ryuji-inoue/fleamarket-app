<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ContactController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;

use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController;

use App\Http\Controllers\ItemController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\SellController;
use App\Http\Controllers\ProfileController;


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


/*
|--------------------------------------------------------------------------
| トップページ（商品一覧）
|--------------------------------------------------------------------------
*/

// 商品一覧（トップ）
Route::get('/', [ItemController::class, 'index'])->name('items.index');

// マイリスト表示（クエリ ?tab=mylist で分岐）
// Route::get('/', [ItemController::class, 'index']);



// =======================
// Auth (認証系)
// =======================

// ユーザ登録
Route::post('/register', [RegisterController::class, 'register'])->name('register');

// ログイン
Route::post('/login', [LoginController::class, 'login']);

// ログアウト
Route::get('/logout', [LoginController::class, 'logout'])->name('auth.logout');

/*
|--------------------------------------------------------------------------
| 商品購入関連（ログイン必須）
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    // 商品購入画面
    Route::get('/purchase/{item_id}', [PurchaseController::class, 'show'])
        ->name('purchase.show')
        ->where('item_id', '[0-9]+');

    // 商品購入処理
    Route::post('/purchase/{item_id}', [PurchaseController::class, 'store'])
        ->name('purchase.store');

    // 住所変更画面
    Route::get('/purchase/address/{item_id}', [PurchaseController::class, 'editAddress'])
        ->name('purchase.address.edit');

    // 住所更新処理
    Route::post('/purchase/address/{item_id}', [PurchaseController::class, 'updateAddress'])
        ->name('purchase.address.update');
});


/*
|--------------------------------------------------------------------------
| 商品出品（ログイン必須）
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    // 出品画面
    Route::get('/sell', [SellController::class, 'create'])
        ->name('sell.create');

    // 出品登録
    Route::post('/sell', [SellController::class, 'store'])
        ->name('sell.store');
});


/*
|--------------------------------------------------------------------------
| マイページ（ログイン必須）
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    // プロフィール画面
    Route::get('/mypage', [ProfileController::class, 'index'])
        ->name('mypage');

    // プロフィール編集画面
    Route::get('/mypage/profile', [ProfileController::class, 'edit'])
        ->name('mypage.profile.edit');

    // プロフィール更新
    Route::post('/mypage/profile', [ProfileController::class, 'update'])
        ->name('mypage.profile.update');
});