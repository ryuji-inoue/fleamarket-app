<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    /**
     * メールアドレスとパスワードが未入力の場合、バリデーションエラーが発生することを確認
     */
    public function test_email_and_password_are_required()
    {
        $response = $this->post('/login', [
            'email' => '',
            'password' => ''
        ]);

        // 'email' と 'password' に対してエラーが返ることを確認
        $response->assertSessionHasErrors(['email', 'password']);
    }

    /**
     * 登録済みユーザーで正しい情報を入力した場合、ログインできることを確認
     */
    public function test_user_can_login_with_correct_credentials()
    {
        $user = User::factory()->create([
            'password' => bcrypt('password123')
        ]);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password123'
        ]);

        // ユーザーが認証済みであることを確認
        $this->assertAuthenticated();

        // ログイン後、商品一覧画面にリダイレクトされることを確認
        $response->assertRedirect('/'); 
    }

    /**
     * 登録されているユーザーだが間違ったパスワードの場合、ログインできないことを確認
     */
    public function test_login_fails_with_wrong_password()
    {
        $user = User::factory()->create([
            'password' => bcrypt('password123')
        ]);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'wrongpassword'
        ]);

        // セッションに 'email' エラーが返ることを確認（Laravelデフォルト）
        $response->assertSessionHasErrors('email');

        // 認証されていないことを確認
        $this->assertGuest();
    }

    /**
     * 登録されていないメールアドレスの場合、ログインできないことを確認
     */
    public function test_login_fails_with_unregistered_email()
    {
        $response = $this->post('/login', [
            'email' => 'notfound@test.com',
            'password' => 'anyPassword123'
        ]);

        // セッションに 'email' エラーが返ることを確認
        $response->assertSessionHasErrors('email');

        $this->assertGuest();
    }
}