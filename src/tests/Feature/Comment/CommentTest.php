<?php

namespace Tests\Feature\Comment;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Comment;

class CommentTest extends TestCase
{
    use RefreshDatabase;

    /**
     * ログイン済みユーザーはコメントを送信できる
     */
    public function test_logged_in_user_can_comment()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        $this->actingAs($user);

        // コメント送信
        $response = $this->post("/comments/{$item->id}", [
            'content' => 'テストコメント'
        ]);

        // リダイレクト確認（通常は詳細ページに戻る）
        $response->assertStatus(302);

        // DBにコメントが登録されているか確認
        $this->assertDatabaseHas('comments', [
            'user_id' => $user->id,
            'item_id' => $item->id,
            'content' => 'テストコメント'
        ]);
    }

    /**
     * 未ログインユーザーはコメントを送信できない
     */
    public function test_guest_cannot_comment()
    {
        $item = Item::factory()->create();

        // コメント送信
        $response = $this->post("/comments/{$item->id}", [
            'content' => 'テストコメント'
        ]);

        // 未認証ユーザーはログインページへリダイレクト
        $response->assertRedirect('/login');

        // DBにコメントが登録されていないことを確認
        $this->assertDatabaseMissing('comments', [
            'content' => 'テストコメント'
        ]);
    }

    /**
     * コメントが空の場合、バリデーションエラーになる
     */
    public function test_comment_cannot_be_empty()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        $this->actingAs($user);

        // 空コメント送信
        $response = $this->post("/comments/{$item->id}", [
            'content' => ''
        ]);

        // バリデーションエラー確認
        $response->assertSessionHasErrors('content');
    }

    /**
     * コメントが255文字を超える場合、バリデーションエラーになる
     */
    public function test_comment_cannot_exceed_255_characters()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        $this->actingAs($user);

        // 256文字のコメントを作成
        $longComment = str_repeat('あ', 256);

        $response = $this->post("/comments/{$item->id}", [
            'content' => $longComment
        ]);

        // バリデーションエラー確認
        $response->assertSessionHasErrors('content');
    }
}