<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    /**
     * ユーザ登録フォーム表示
     */
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    /**
     * ユーザ登録処理
     */
    public function register(RegisterRequest $request)
    {
        // バリデーション済みのデータを取得
        $data = $request->validated();

        // ユーザ登録
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']), // パスワードはハッシュ化
        ]);

        // 自動ログイン
        Auth::login($user);

        // 登録後のリダイレクト
        return redirect('/admin')->with('success', 'ユーザ登録が完了しました。');
    }
}