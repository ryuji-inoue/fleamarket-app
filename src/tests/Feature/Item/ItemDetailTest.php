<?php

namespace Tests\Feature\Item;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Models\Item;

class ItemDetailTest extends TestCase
{

    public function test_item_detail_display()
    {
        $item = Item::factory()->create();

        $response = $this->get("/items/{$item->id}");

        $response->assertStatus(200);
    }
}
