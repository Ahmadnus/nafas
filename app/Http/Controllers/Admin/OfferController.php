<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Offer;
use App\Models\Product;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class OfferController extends Controller
{
    public function index()
    {
        $offers = Offer::with('product')->orderBy('sort_order')->get();

        return view('admin.offers.index', compact('offers'));
    }

    public function create()
    {
        $products = Product::orderBy('title')->get();

        return view('admin.offers.form', [
            'offer' => new Offer(),
            'products' => $products,
            'enableEnglish' => Setting::get('enable_english', '1') === '1',
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('offers', 'public');
        }

        Offer::create($data);

        return redirect()->route('admin.offers.index')->with('status', 'تم إنشاء العرض.');
    }

    public function edit(Offer $offer)
    {
        $products = Product::orderBy('title')->get();
        $enableEnglish = Setting::get('enable_english', '1') === '1';

        return view('admin.offers.form', compact('offer', 'products', 'enableEnglish'));
    }

    public function update(Request $request, Offer $offer)
    {
        $data = $this->validated($request);

        if ($request->hasFile('image')) {
            if ($offer->image) {
                Storage::disk('public')->delete($offer->image);
            }
            $data['image'] = $request->file('image')->store('offers', 'public');
        }

        $offer->update($data);

        return redirect()->route('admin.offers.index')->with('status', 'تم تحديث العرض.');
    }

    public function destroy(Offer $offer)
    {
        if ($offer->image) {
            Storage::disk('public')->delete($offer->image);
        }
        $offer->delete();

        return back()->with('status', 'تم حذف العرض.');
    }

    private function validated(Request $request): array
    {
        $validated = $request->validate([
            'product_id' => ['nullable', 'exists:products,id'],
            'title' => ['nullable', 'string', 'max:255'],
            'title_ar' => ['nullable', 'string', 'max:255'],
            'subtitle' => ['nullable', 'string', 'max:255'],
            'subtitle_ar' => ['nullable', 'string', 'max:255'],
            'discount_price' => ['nullable', 'numeric', 'min:0'],
            'starts_at' => ['nullable', 'date'],
            'ends_at' => ['nullable', 'date'],
            'is_active' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer'],
            'image' => ['nullable', 'image', 'max:4096'],
        ]);

        $validated['title'] = ($validated['title'] ?? null) ?: ($validated['title_ar'] ?? null);
        $validated['title_ar'] = ($validated['title_ar'] ?? null) ?: $validated['title'];
        $validated['subtitle'] = ($validated['subtitle'] ?? null) ?: ($validated['subtitle_ar'] ?? null);
        $validated['subtitle_ar'] = ($validated['subtitle_ar'] ?? null) ?: $validated['subtitle'];

        if (! $validated['title']) {
            throw \Illuminate\Validation\ValidationException::withMessages(['title_ar' => 'العنوان مطلوب.']);
        }

        $validated['is_active'] = $request->boolean('is_active');
        $validated['sort_order'] ??= 0;
        unset($validated['image']);

        return $validated;
    }
}
