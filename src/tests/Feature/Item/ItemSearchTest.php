<?php

namespace Tests\Feature\Item;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Models\Item;

class ItemSearchTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_search_items()
    {
        Item::factory()->create([
            'name'=>'iPhone'
        ]);

        Item::factory()->create([
            'name'=>'Macbook'
        ]);

        $response = $this->get('/?keyword=iPhone');

        $response->assertSee('iPhone');
    }
}
