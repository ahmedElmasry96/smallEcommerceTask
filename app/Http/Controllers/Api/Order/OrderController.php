<?php

namespace App\Http\Controllers\Api\Order;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Order\MakeOrderRequest;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Repositories\Order\OrderRepositoryInterface;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class OrderController extends Controller
{
    use ApiResponseTrait;

    protected OrderRepositoryInterface $orderRepository;

    public function __construct(OrderRepositoryInterface $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    public function makeOrder(MakeOrderRequest $request)
    {
        try {
            DB::beginTransaction();
            $order = $this->orderRepository->createOrder($request);
            DB::commit();
            return $this->returnData('data', new OrderResource($order), $this->getSuccessMessage());
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->returnError($e->getMessage(), 400);
        }
    }

    public function show($id)
    {
        $user = auth('api')->user();
        $order = $this->orderRepository->show($id);
        if($order->user_id != $user->id) {
            return $this->returnError('you cannot view this order details', Response::HTTP_BAD_REQUEST);
        }
        return $this->returnData('data', new OrderResource($order), $this->getSuccessMessage());
    }
}
