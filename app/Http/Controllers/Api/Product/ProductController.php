<?php

namespace App\Http\Controllers\Api\Product;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Product\StoreProductRequest;
use App\Http\Requests\Api\Product\UpdateProductRequest;
use App\Http\Resources\ProductResource;
use App\Repositories\Product\ProductRepositoryInterface;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ProductController extends Controller
{
    use ApiResponseTrait;

    protected ProductRepositoryInterface $productRepository;

    public function __construct(ProductRepositoryInterface $productRepository)
    {
        $this->productRepository = $productRepository;
    }
    public function index(Request $request)
    {
        $products = $this->productRepository->filterProducts($request);
        return $this->returnData('data', ProductResource::collection($products)->response()->getData(), $this->getSuccessMessage());
    }

    public function store(StoreProductRequest $request)
    {
        try {
            $this->productRepository->store($request);
            return $this->returnSuccessMessage('Product created successfully.', Response::HTTP_CREATED);
        } catch (\Exception $e)
        {
            return $this->returnError($this->getFailedMessage(), Response::HTTP_BAD_REQUEST);
        }
    }

    public function update(UpdateProductRequest $request)
    {
        try {
            $this->productRepository->update($request);
            return $this->returnSuccessMessage('Product updated successfully.', Response::HTTP_OK);
        } catch (\Exception $e)
        {
            return $this->returnError($this->getFailedMessage(), Response::HTTP_BAD_REQUEST);
        }
    }
}
