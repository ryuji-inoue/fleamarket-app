<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ContactController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;

use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController;



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

// =======================
// Contact (お問い合わせ系)
// =======================

// お問い合わせフォーム入力ページ
Route::get('/', [ContactController::class, 'index'])->name('contact.index');

// お問い合わせフォーム確認ページ
Route::post('/confirm', [ContactController::class, 'confirm'])->name('contact.confirm');

// PG03 サンクスページ
Route::post('/thanks', [ContactController::class, 'thanks'])->name('contact.thanks');
Route::get('/thanks', function () {return view('contact.thanks');});

// =======================
// Admin (管理系)
// =======================

// 管理画面
Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');

// 検索リセット
Route::get('/reset', [AdminController::class, 'reset'])->name('admin.reset');

// お問い合わせフォーム削除
Route::delete('/delete/{id}', [AdminController::class, 'delete'])->name('admin.delete');

// エクスポート
Route::get('/export', [AdminController::class, 'export'])->name('admin.export');


// =======================
// Auth (認証系)
// =======================

// ユーザ登録
Route::post('/register', [RegisterController::class, 'register'])->name('register');

// ログイン
Route::post('/login', [LoginController::class, 'login']);

// ログアウト
Route::get('/logout', [LoginController::class, 'logout'])->name('auth.logout');
