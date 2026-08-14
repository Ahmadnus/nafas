<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Offer;
use App\Models\Setting;

class MenuController extends Controller
{
    public function index()
    {
        $categories = Category::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->with(['products' => function ($q) {
                $q->where('is_available', true)
                    ->orderBy('sort_order')
                    ->with(['optionGroups.options']);
            }])
            ->get();

        $offers = Offer::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->with('product.optionGroups.options')
            ->get();

        $settings = [
            'restaurant_name' => Setting::get('restaurant_name', 'Nafas - Juice & Patisserie'),
            'restaurant_name_ar' => Setting::get('restaurant_name_ar', ''),
            'tagline' => Setting::get('tagline', ''),
            'tagline_ar' => Setting::get('tagline_ar', ''),
            'whatsapp_number' => Setting::get('whatsapp_number', ''),
            'currency_symbol' => Setting::get('currency_symbol', 'Rs.'),
            'is_open' => Setting::get('is_open', '1') === '1',
            'enable_english' => Setting::get('enable_english', '1') === '1',
            'instagram_url' => Setting::get('instagram_url', ''),
            'facebook_url' => Setting::get('facebook_url', ''),
            'tiktok_url' => Setting::get('tiktok_url', ''),
            'location_url' => Setting::get('location_url', ''),
        ];

        $popularProductIds = \App\Models\Product::query()
            ->where('clicks_count', '>', 0)
            ->orderByDesc('clicks_count')
            ->limit(6)
            ->pluck('id');

        return view('menu.index', compact('categories', 'offers', 'settings', 'popularProductIds'));
    }
}
