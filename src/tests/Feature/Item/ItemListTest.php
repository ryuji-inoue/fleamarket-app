<?php

namespace Tests\Feature\Item;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Models\Item;

class ItemListTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_all_items_are_displayed()
    {
        Item::factory()->count(5)->create();

        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_sold_label_is_displayed_for_purchased_items()
    {
        $item = Item::factory()->create([
            'status' => 1
        ]);

        $response = $this->get('/');

        $response->assertSee('Sold');
    }
}
