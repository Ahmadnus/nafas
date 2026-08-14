<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    private array $keys = [
        'restaurant_name', 'restaurant_name_ar', 'tagline', 'tagline_ar',
        'whatsapp_number', 'currency_symbol', 'is_open', 'enable_english',
        'instagram_url', 'facebook_url', 'tiktok_url', 'location_url',
    ];

    public function edit()
    {
        $settings = collect($this->keys)->mapWithKeys(fn ($key) => [$key => Setting::get($key, '')]);

        return view('admin.settings.edit', compact('settings'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'restaurant_name' => ['required', 'string', 'max:255'],
            'restaurant_name_ar' => ['nullable', 'string', 'max:255'],
            'tagline' => ['nullable', 'string', 'max:255'],
            'tagline_ar' => ['nullable', 'string', 'max:255'],
            'whatsapp_number' => ['required', 'string', 'max:20'],
            'currency_symbol' => ['required', 'string', 'max:10'],
            'is_open' => ['nullable', 'boolean'],
            'enable_english' => ['nullable', 'boolean'],
            'instagram_url' => ['nullable', 'string', 'max:255'],
            'facebook_url' => ['nullable', 'string', 'max:255'],
            'tiktok_url' => ['nullable', 'string', 'max:255'],
            'location_url' => ['nullable', 'string', 'max:255'],
        ]);

        $validated['is_open'] = $request->boolean('is_open') ? '1' : '0';
        $validated['enable_english'] = $request->boolean('enable_english') ? '1' : '0';

        foreach ($validated as $key => $value) {
            Setting::set($key, (string) $value);
        }

        return back()->with('status', 'تم تحديث الإعدادات.');
    }
}
