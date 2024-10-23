<?php

namespace Tests\Unit;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Repositories\Order\OrderRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\MockObject\MockObject;
use Tests\TestCase;

class OrderRepositoryTest extends TestCase
{
    use RefreshDatabase;

    /** @var OrderRepository|MockObject */
    protected $orderRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->orderRepository = $this->createMock(OrderRepository::class);
    }

    #[Test]
    public function it_creates_an_order()
    {
        $user = User::factory()->create();
        $product = Product::factory()->create([
            'quantity' => 10,
            'price' => 100,
        ]);

        $request = new Request([
            'user_id' => $user->id,
            'total_price' => 200,
            'payment_method' => Order::COD,
            'payment_status' => Order::unPaid,
            'products' => [
                ['id' => $product->id, 'quantity' => 2],
            ],
        ]);

        $order = Order::factory()->make();
        $this->orderRepository->method('createOrder')->willReturn($order);

        $result = $this->orderRepository->createOrder($request);

        $this->assertInstanceOf(Order::class, $result);
    }
}
