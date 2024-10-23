<?php

namespace App\Repositories\Order;

use Illuminate\Http\Request;

interface OrderRepositoryInterface
{
    public function createOrder(Request $request);
    public function storeOrderItems(array $products, $orderId);
    public function updateProductQuantity($product, $quantity);
    public function show($id);

}
