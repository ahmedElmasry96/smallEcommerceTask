<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class OrderPlacementTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_places_an_order_successfully()
    {
        $user = User::factory()->create();
        $product = Product::factory()->create([
            'quantity' => 10,
            'price' => 100,
        ]);

        $this->actingAs($user);

        $response = $this->postJson('/api/order/make', [
            'total_price' => 200,
            'payment_method' => Order::COD,
            'payment_status' => Order::unPaid,
            'products' => [
                ['id' => $product->id, 'quantity' => 2],
            ],
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'status' => true,
                'msg' => 'success',
            ]);

        $this->assertDatabaseHas('orders', [
            'user_id' => $user->id,
            'total_price' => 200,
        ]);

        $this->assertDatabaseHas('order_items', [
            'product_id' => $product->id,
            'quantity' => 2,
        ]);

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'quantity' => 8,
        ]);
    }

    #[Test]
    public function it_fails_to_place_an_order_if_product_quantity_is_insufficient()
    {
        $user = User::factory()->create();
        $product = Product::factory()->create([
            'quantity' => 1,
            'price' => 100,
        ]);

        $this->actingAs($user);

        $response = $this->postJson('/api/order/make', [
            'total_price' => 200,
            'payment_method' => Order::COD,
            'payment_status' => Order::unPaid,
            'products' => [
                ['id' => $product->id, 'quantity' => 2],
            ],
        ]);

        $response->assertStatus(400)
        ->assertJson([
            'status' => false,
            'msg' => 'Product ' . $product->name . ' is out of stock',
        ]);

        $this->assertDatabaseMissing('orders', [
            'user_id' => $user->id,
        ]);
    }
}
