<x-layouts.admin title="المنتجات">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold">المنتجات</h1>
        <a href="{{ route('admin.products.create') }}" class="bg-brand hover:bg-brand-dark text-white text-sm font-semibold px-4 py-2.5 rounded-xl flex items-center gap-2">
            <i data-lucide="plus" class="w-4 h-4"></i> منتج جديد
        </a>
    </div>

    <form method="GET" class="flex gap-3 mb-4">
        <input type="text" name="q" value="{{ request('q') }}" placeholder="ابحث عن منتج..."
            class="flex-1 rounded-xl border border-border px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand/40">
        <select name="category" class="rounded-xl border border-border px-4 py-2.5 text-sm">
            <option value="">كل الأقسام</option>
            @foreach($categories as $category)
            <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>{{ $category->name_ar ?: $category->name }}</option>
            @endforeach
        </select>
        <button class="bg-surface-alt border border-border px-4 py-2.5 rounded-xl text-sm font-medium">تصفية</button>
    </form>

    <div class="bg-surface-raised border border-border rounded-2xl overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-surface-alt text-text-muted text-xs uppercase">
                <tr>
                    <th class="text-start px-5 py-3">المنتج</th>
                    <th class="text-start px-5 py-3">القسم</th>
                    <th class="text-start px-5 py-3">السعر</th>
                    <th class="text-start px-5 py-3">الحالة</th>
                    <th class="px-5 py-3"></th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $product)
                <tr class="border-t border-border">
                    <td class="px-5 py-3">
                        <div class="flex items-center gap-3">
                            <img src="{{ $product->image_url }}" class="w-10 h-10 rounded-lg object-cover bg-surface-alt">
                            <span class="font-medium">{{ $product->title_ar ?: $product->title }}</span>
                        </div>
                    </td>
                    <td class="px-5 py-3 text-text-muted">{{ $product->category->name_ar ?: $product->category->name }}</td>
                    <td class="px-5 py-3 font-medium">Rs. {{ number_format($product->price, 0) }}</td>
                    <td class="px-5 py-3">
                        <span @class([
                            'text-xs font-semibold px-2 py-1 rounded-full',
                            'bg-brand/10 text-brand' => $product->is_available,
                            'bg-danger/10 text-danger' => !$product->is_available,
                        ])>{{ $product->is_available ? 'متوفر' : 'نفدت الكمية' }}</span>
                    </td>
                    <td class="px-5 py-3 text-end">
                        <div class="flex items-center gap-3 justify-end">
                            <a href="{{ route('admin.products.edit', $product) }}" class="text-text-muted hover:text-brand">
                                <i data-lucide="pencil" class="w-4 h-4"></i>
                            </a>
                            <form method="POST" action="{{ route('admin.products.destroy', $product) }}" onsubmit="return confirm('حذف هذا المنتج؟')">
                                @csrf @method('DELETE')
                                <button class="text-text-muted hover:text-danger">
                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="px-5 py-8 text-center text-text-muted">لم يتم العثور على منتجات.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $products->links() }}</div>
</x-layouts.admin>
