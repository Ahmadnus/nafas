<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::query()
            ->with('category')
            ->when($request->filled('category'), fn ($q) => $q->where('category_id', $request->category))
            ->when($request->filled('q'), fn ($q) => $q->where('title', 'like', '%' . $request->q . '%'))
            ->orderBy('sort_order')
            ->paginate(15)
            ->withQueryString();

        $categories = Category::orderBy('sort_order')->get();

        return view('admin.products.index', compact('products', 'categories'));
    }

    public function create()
    {
        $categories = Category::orderBy('sort_order')->get();

        return view('admin.products.form', [
            'product' => new Product(),
            'categories' => $categories,
            'enableEnglish' => Setting::get('enable_english', '1') === '1',
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $data['slug'] = (Str::slug($data['title']) ?: 'item') . '-' . Str::random(4);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        $product = Product::create($data);
        $this->syncOptionGroups($product, $request);

        return redirect()->route('admin.products.index')->with('status', 'تم إنشاء المنتج.');
    }

    public function edit(Product $product)
    {
        $categories = Category::orderBy('sort_order')->get();
        $product->load('optionGroups.options');
        $enableEnglish = Setting::get('enable_english', '1') === '1';

        return view('admin.products.form', compact('product', 'categories', 'enableEnglish'));
    }

    public function update(Request $request, Product $product)
    {
        $data = $this->validated($request);

        if ($request->hasFile('image')) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        $product->update($data);
        $this->syncOptionGroups($product, $request);

        return redirect()->route('admin.products.index')->with('status', 'تم تحديث المنتج.');
    }

    public function destroy(Product $product)
    {
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }
        $product->delete();

        return back()->with('status', 'تم حذف المنتج.');
    }

    private function validated(Request $request): array
    {
        $validated = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'title' => ['nullable', 'string', 'max:255'],
            'title_ar' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'description_ar' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'compare_price' => ['nullable', 'numeric', 'min:0'],
            'badges' => ['nullable', 'array'],
            'badges.*' => ['string'],
            'is_available' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer'],
            'image' => ['nullable', 'image', 'max:4096'],
        ]);

        $validated['title'] = ($validated['title'] ?? null) ?: ($validated['title_ar'] ?? null);
        $validated['title_ar'] = ($validated['title_ar'] ?? null) ?: $validated['title'];
        $validated['description'] = ($validated['description'] ?? null) ?: ($validated['description_ar'] ?? null);
        $validated['description_ar'] = ($validated['description_ar'] ?? null) ?: $validated['description'];

        if (! $validated['title']) {
            throw \Illuminate\Validation\ValidationException::withMessages(['title_ar' => 'العنوان مطلوب.']);
        }

        $validated['is_available'] = $request->boolean('is_available');
        $validated['badges'] = $validated['badges'] ?? [];
        $validated['sort_order'] ??= 0;
        unset($validated['image']);

        return $validated;
    }

    private function syncOptionGroups(Product $product, Request $request): void
    {
        $groups = $request->input('groups', []);

        $product->optionGroups()->delete();

        foreach ($groups as $gi => $group) {
            if (empty($group['name'])) {
                continue;
            }

            $groupModel = $product->optionGroups()->create([
                'name' => $group['name'],
                'name_ar' => $group['name_ar'] ?? null,
                'type' => $group['type'] ?? 'single',
                'is_required' => ! empty($group['is_required']),
                'sort_order' => $gi,
            ]);

            foreach ($group['options'] ?? [] as $oi => $option) {
                if (empty($option['label'])) {
                    continue;
                }

                $groupModel->options()->create([
                    'label' => $option['label'],
                    'label_ar' => $option['label_ar'] ?? null,
                    'price_delta' => $option['price_delta'] ?? 0,
                    'is_default' => ! empty($option['is_default']),
                    'sort_order' => $oi,
                ]);
            }
        }
    }
}
