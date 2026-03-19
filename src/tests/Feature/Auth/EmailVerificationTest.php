<?php

namespace Tests\Feature\Auth;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Notification;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EmailVerificationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * ① 会員登録後、認証メールが送信される
     */
    public function test_verification_email_is_sent_after_registration()
    {
        Notification::fake();

        $response = $this->post('/register', [
            'name' => 'test user',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $user = User::where('email', 'test@example.com')->first();

        Notification::assertSentTo($user, VerifyEmail::class);
    }

    /**
     * ② 認証誘導画面のリンク遷移
     */
    public function test_verify_notice_screen_has_link()
    {
        $user = User::factory()->unverified()->create();

        $response = $this->actingAs($user)->get('/email/verify');

        $response->assertStatus(200);
        $response->assertSee('認証はこちらから');
    }

    /**
     * ③ メール認証完了 → プロフィール画面へ遷移
     */
    public function test_user_is_redirected_after_email_verification()
    {
        $user = User::factory()->unverified()->create();

        // 署名付きURLを生成
        $verificationUrl = \Illuminate\Support\Facades\URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            [
                'id' => $user->id,
                'hash' => sha1($user->email),
            ]
        );

        $response = $this->actingAs($user)->get($verificationUrl);

        // 認証済みになっているか
        $this->assertTrue($user->fresh()->hasVerifiedEmail());

        // プロフィール画面へリダイレクト
        $response->assertRedirect('/profile/create');
    }
}