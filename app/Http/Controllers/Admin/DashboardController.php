<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Offer;
use App\Models\Product;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'products' => Product::count(),
            'categories' => Category::count(),
            'offers' => Offer::where('is_active', true)->count(),
            'unavailable' => Product::where('is_available', false)->count(),
        ];

        $recentProducts = Product::with('category')->latest()->limit(5)->get();

        $popularProducts = Product::with('category')
            ->where('clicks_count', '>', 0)
            ->orderByDesc('clicks_count')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentProducts', 'popularProducts'));
    }
}
