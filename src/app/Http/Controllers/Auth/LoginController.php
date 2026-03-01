<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * ログインフォーム表示
     */
    public function index()
    {
        return view('auth.login');
    }

    /**
     * ログイン処理
     */
    public function login(LoginRequest $request)
    {
        // バリデーション済みのデータを取得
        $credentials = $request->validated();

        // 認証試行
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate(); // セッション再生成（セキュリティ対策）
            return redirect()->intended('/admin'); // ログイン後のリダイレクト
        }

        // 認証失敗
        return back()->withErrors([
            'email' => 'ログイン情報が登録されていません',
        ])->withInput();
    }

    /**
     * ログアウト
     */
    public function logout(Request $request)
    {
        Auth::logout();

        // セッションをクリアして再生成
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}