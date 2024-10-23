<?php

namespace App\Repositories\Product;

use App\Models\Product;
use App\Traits\FileTrait;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ProductRepository implements ProductRepositoryInterface
{
    use FileTrait;
    public function filterProducts($filters): LengthAwarePaginator
    {
        $cacheKey = $this->generateCacheKey($filters);

        return Cache::remember($cacheKey, now()->addHour(), function() use ($filters) {
            $searchTerm = $filters->input('search');
            $minPrice = $filters->input('min_price');
            $maxPrice = $filters->input('max_price');

            return Product::query()
                ->when($searchTerm, function ($query, $searchTerm) {
                    return $query->search($searchTerm);
                })
                ->when($minPrice || $maxPrice, function ($query) use ($minPrice, $maxPrice) {
                    return $query->priceRange($minPrice, $maxPrice);
                })
                ->paginate(10);
        });
    }

    protected function generateCacheKey($filters): string
    {
        $searchTerm = $filters->input('search', '');
        $minPrice = $filters->input('min_price', '');
        $maxPrice = $filters->input('max_price', '');
        $page = $filters->input('page', 1);

        return "products:search={$searchTerm}:min_price={$minPrice}:max_price={$maxPrice}:page={$page}";
    }

    public function store(Request $request): void
    {
        $imagePath = $this->uploadImage($request->image, 'uploads/products/');

        Product::create([
            'name' => $request->name,
            'description' => $request->description,
            'image' => $imagePath,
            'price' => $request->price,
            'quantity' => $request->quantity,
        ]);

        Cache::flush();
    }

    public function update(Request $request): void
    {
        $product = Product::findOrFail($request->product_id);
        $product->update([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'quantity' => $request->quantity,
        ]);

        if ($request->image) {
            $this->removeImage($product->image);
            $imagePath = $this->uploadImage($request->image, 'uploads/products/');
            $product->update([
                'image' => $imagePath,
            ]);
        }

        Cache::flush();
    }
}
