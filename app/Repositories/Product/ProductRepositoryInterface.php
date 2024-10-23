<?php

namespace App\Repositories\Product;

use Illuminate\Http\Request;

interface ProductRepositoryInterface
{
    public function filterProducts($filters);
    public function store(Request $request);
    public function update(Request $request);

}
