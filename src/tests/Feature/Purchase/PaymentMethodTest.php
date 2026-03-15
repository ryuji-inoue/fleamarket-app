<?php

namespace Tests\Feature\Purchase;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PaymentMethodTest extends TestCase
{
    public function test_purchase_item()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        $this->actingAs($user);

        $this->post("/purchase/{$item->id}",[
            'payment_id'=>1
        ]);

        $this->assertDatabaseHas('purchases',[
            'item_id'=>$item->id
        ]);
    }
}
