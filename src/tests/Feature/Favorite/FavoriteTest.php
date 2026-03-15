<?php

namespace Tests\Feature\Favorite;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Models\User;
use App\Models\Item;

class FavoriteTest extends TestCase
{
    use RefreshDatabase;

    public function test_favorite_item()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        Favorite::create([
            'user_id'=>$user->id,
            'item_id'=>$item->id
        ]);

        $this->actingAs($user);

        $response = $this->get('/mypage?tab=mylist');

        $response->assertStatus(200);
    }

    
}
