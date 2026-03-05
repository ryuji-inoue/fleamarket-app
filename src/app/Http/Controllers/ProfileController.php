<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProfileController extends Controller
{
    /* プロフィール画面 */
    public function show()
    {
        return view('profile.show');
    }

    /* プロフィール編集画面 */
    public function edit()
    {
        return view('profile.edit');
    }
}