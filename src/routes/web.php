<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request; 

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;

use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController;

use App\Http\Controllers\ItemController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\ProfileController;

use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\CommentController;

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
Route::get('/mylist', [ItemController::class, 'mylist'])->name('items.mylist');

// 商品詳細
Route::get('/items/{item}', [ItemController::class, 'show'])->name('items.show');


/*
|--------------------------------------------------------------------------
| 認証必須ルート（ログインユーザ専用）
|--------------------------------------------------------------------------
| 商品購入、出品、マイページ、コメント・お気に入りなど
*/
Route::middleware('auth')->group(function () {

    // ---------------------
    // 商品購入関連
    // ---------------------
    // 購入画面
    Route::get('/purchase/{item}', [PurchaseController::class, 'create'])->name('purchase.create'); 

    // Stripe 決済処理
    Route::post('/purchase/{item}/stripe', [PurchaseController::class, 'stripeCheckout'])->name('purchase.stripe'); 

    // Stripe 決済成功
    Route::get('/purchase/success/{item}', [PurchaseController::class, 'success'])->name('purchase.success'); 

    // 住所変更画面
    Route::get('/purchase/address/{item}', [PurchaseController::class, 'editAddress'])->name('address.edit'); 
    Route::post('/purchase/address/{item}', [PurchaseController::class, 'updateAddress'])->name('purchase.updateAddress'); 

    // ---------------------
    // 商品出品関連
    // ---------------------
    // 出品画面
    Route::get('/sell', [ItemController::class, 'create'])->name('items.sell'); 

    // 出品登録
    Route::post('/sell', [ItemController::class, 'store'])->name('items.store'); 

    // ---------------------
    // マイページ関連
    // ---------------------
    // プロフィール画面
    Route::get('/mypage', [ProfileController::class, 'show'])->name('mypage'); 

    // プロフィール編集
    Route::get('/mypage/edit', [ProfileController::class, 'edit'])->name('mypage.profile');

    // プロフィール更新処理
    Route::post('/mypage/profile', [ProfileController::class, 'update'])->name('mypage.profile.update'); 

    // ---------------------
    // お気に入り・コメント
    // ---------------------
     // お気に入り登録
    Route::post('/favorite/{item}', [FavoriteController::class, 'store'])->name('favorite.store');
     // コメント投稿
    Route::post('/comments/{item}', [CommentController::class, 'store'])->name('comments.store');
});


/*
|--------------------------------------------------------------------------
| 認証系（非ログインも可）
|--------------------------------------------------------------------------
*/

// ユーザ登録
Route::post('/register', [RegisterController::class, 'register'])->name('register');

// ログイン
Route::post('/login', [LoginController::class, 'login']);

// ログアウト
Route::get('/logout', [LoginController::class, 'logout'])->name('auth.logout');

/*
|--------------------------------------------------------------------------
| メール認証
|--------------------------------------------------------------------------
*/

// 認証誘導画面
Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

// 認証処理
Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill(); // 認証済みにする
    return redirect('/mypage/edit'); // プロフィール編集画面へリダイレクト
})->middleware(['auth', 'signed'])->name('verification.verify');

// 認証メール再送
Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification(); // メール送信
    return back()->with('message', '認証メールを再送しました');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');