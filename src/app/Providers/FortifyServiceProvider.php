<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Http\Requests\LoginRequest;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Laravel\Fortify\Fortify;

class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot()
    {

        Fortify::createUsersUsing(CreateNewUser::class);

        // 登録ページ
        Fortify::registerView(function () {
            return view('auth.register');
        });

        // ログインページ
        Fortify::loginView(function () {
            return view('auth.login');
        });
        
        // ログインレートリミット
        RateLimiter::for('login', function (Request $request) {
        $email = (string) $request->email;

         return Limit::perMinute(10)->by($email . $request->ip());
        });  
    }
}
