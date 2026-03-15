<?php

namespace Tests\Feature\Address;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Models\User;
use App\Models\Item;
use App\Models\Condition;

class AddressTest extends TestCase
{
    use RefreshDatabase;

    public function test_updated_address_is_reflected_on_purchase_page()
    {
        $user = User::factory()->create();
        $condition = Condition::factory()->create();

        $item = Item::factory()->create([
            'condition_id' => $condition->id
        ]);

        $this->actingAs($user);

        $this->post("/purchase/address/{$item->id}", [
            'postal_code' => '123-4567',
            'address' => '東京都渋谷区',
            'building' => 'テストビル'
        ]);

        $this->assertEquals(
            '123-4567',
            session('purchase_address.postal_code')
        );
    }

    public function test_address_is_saved_when_item_is_purchased()
    {
        $user = User::factory()->create([
            'postal_code' => '123-4567',
            'address' => '東京都渋谷区',
            'building' => 'テストビル'
        ]);

        $condition = Condition::factory()->create();

        $item = Item::factory()->create([
            'condition_id' => $condition->id
        ]);

        $this->actingAs($user);

        session([
            'purchase_address' => [
                'postal_code'=>'123-4567',
                'address'=>'東京都渋谷区',
                'building'=>'テストビル'
            ]
        ]);

        $this->post("/purchase/{$item->id}",[
            'payment_id' => 1
        ]);

        // purchasesテーブル確認
        $this->assertDatabaseHas('purchases',[
            'item_id' => $item->id,
            'postal_code' => '123-4567',
            'address' => '東京都渋谷区'
        ]);
    }
}