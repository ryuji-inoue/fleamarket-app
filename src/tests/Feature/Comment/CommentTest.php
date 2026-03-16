<?php

namespace Tests\Feature\Comment;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Models\User;
use App\Models\Item;

class CommentTest extends TestCase
{
    public function test_comment_store()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        $this->actingAs($user);

        $this->post("/comments/{$item->id}",[
            'content'=>'テストコメント'
        ]);

        $this->assertDatabaseHas('comments',[
            'content'=>'テストコメント'
        ]);
    }
}
