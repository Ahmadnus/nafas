<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('products')->orderBy('sort_order')->get();

        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.categories.form', [
            'category' => new Category(),
            'enableEnglish' => Setting::get('enable_english', '1') === '1',
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $data['slug'] = (Str::slug($data['name']) ?: 'category') . '-' . Str::random(4);

        Category::create($data);

        return redirect()->route('admin.categories.index')->with('status', 'تم إنشاء القسم.');
    }

    public function edit(Category $category)
    {
        $enableEnglish = Setting::get('enable_english', '1') === '1';

        return view('admin.categories.form', compact('category', 'enableEnglish'));
    }

    public function update(Request $request, Category $category)
    {
        $data = $this->validated($request);
        $data['slug'] = Str::slug($data['name']) ?: $category->slug;

        $category->update($data);

        return redirect()->route('admin.categories.index')->with('status', 'تم تحديث القسم.');
    }

    public function destroy(Category $category)
    {
        if ($category->products()->exists()) {
            return back()->with('error', 'لا يمكن حذف هذا القسم لأنه يحتوي على منتجات. احذف أو انقل المنتجات أولاً.');
        }

        $category->delete();

        return back()->with('status', 'تم حذف القسم.');
    }

    private function validated(Request $request): array
    {
        $validated = $request->validate([
            'name' => ['nullable', 'string', 'max:255'],
            'name_ar' => ['nullable', 'string', 'max:255'],
            'icon' => ['nullable', 'string', 'max:100'],
            'sort_order' => ['nullable', 'integer'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $validated['name'] = ($validated['name'] ?? null) ?: ($validated['name_ar'] ?? null);
        $validated['name_ar'] = ($validated['name_ar'] ?? null) ?: $validated['name'];

        if (! $validated['name']) {
            throw \Illuminate\Validation\ValidationException::withMessages(['name_ar' => 'اسم القسم مطلوب.']);
        }

        $validated['sort_order'] ??= 0;
        $validated['is_active'] = $request->boolean('is_active');

        return $validated;
    }
}
