<?php

namespace App\Repositories\Order;

use App\Events\OrderPlaced;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Cache;

class OrderRepository implements OrderRepositoryInterface
{
    use ApiResponseTrait;
    public function createOrder($request)
    {
        $total = $this->getTotalPrice($request->products);
        $order = Order::create([
            'user_id' => auth()->id(),
            'total_price' => $total,
            'tax_value' => 0,
            'discount_value' => 0,
            'payment_status' => Order::unPaid,
            'payment_method' => $request->payment_method,
        ]);

        $this->storeOrderItems($request->products, $order->id);
        event(new OrderPlaced($order));
        return $order;
    }

    public function storeOrderItems(array $products, $orderId): void
    {
        foreach ($products as $productData) {
            $product = Product::find($productData['id']);
            $this->updateProductQuantity($product, $productData['quantity']);
            OrderItem::create([
                'order_id' => $orderId,
                'product_id' => $product->id,
                'quantity' => $productData['quantity'],
            ]);
        }
    }

    public function getTotalPrice($products)
    {
        $total = 0;
        foreach($products as $productData){
            $product = Product::findOrFail($productData['id']);
            $total += $product->price * $productData['quantity'];
        }

        return $total;
    }

    /**
     * @throws \Exception
     */
    public function updateProductQuantity($product, $quantity): void
    {
        if ($product->quantity < $quantity) {
            throw new \Exception('Product ' . $product->name . ' is out of stock');
        }

        $product->update([
            'quantity' => $product->quantity - $quantity
        ]);

        Cache::flush();
    }

    /**
     * @throws \Exception
     */
    public function show($id)
    {
        $order = Order::find($id);
        if ($order) {
            return $order;
        }
        throw new HttpResponseException(response()->json([
            'status'   => false,
            'msg'   => 'Order Not Found'
        ]));
    }
}
