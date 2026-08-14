<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\JsonResponse;

class ProductClickController extends Controller
{
    public function store(Product $product): JsonResponse
    {
        $product->increment('clicks_count');

        return response()->json(['clicks_count' => $product->clicks_count]);
    }
}
