<x-layouts.admin title="لوحة التحكم">
    <h1 class="text-2xl font-bold mb-6">لوحة التحكم</h1>

    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        @foreach([
            ['label' => 'إجمالي المنتجات', 'value' => $stats['products'], 'icon' => 'shopping-basket'],
            ['label' => 'الأقسام', 'value' => $stats['categories'], 'icon' => 'list-tree'],
            ['label' => 'العروض النشطة', 'value' => $stats['offers'], 'icon' => 'megaphone'],
            ['label' => 'نفدت الكمية', 'value' => $stats['unavailable'], 'icon' => 'ban'],
        ] as $card)
        <div class="bg-surface-raised border border-border rounded-2xl p-5">
            <div class="w-9 h-9 rounded-xl bg-brand/10 text-brand flex items-center justify-center mb-3">
                <i data-lucide="{{ $card['icon'] }}" class="w-4 h-4"></i>
            </div>
            <p class="text-2xl font-bold">{{ $card['value'] }}</p>
            <p class="text-sm text-text-muted">{{ $card['label'] }}</p>
        </div>
        @endforeach
    </div>

    <div class="bg-surface-raised border border-border rounded-2xl overflow-hidden mb-8">
        <div class="px-5 py-4 border-b border-border font-semibold flex items-center gap-2">
            <i data-lucide="flame" class="w-4 h-4 text-accent-3"></i>
            الأكثر طلباً / زيارةً
        </div>
        <table class="w-full text-sm">
            <tbody>
                @forelse($popularProducts as $product)
                <tr class="border-b border-border last:border-0">
                    <td class="px-5 py-3">
                        <div class="flex items-center gap-3">
                            <img src="{{ $product->image_url }}" class="w-10 h-10 rounded-lg object-cover bg-surface-alt">
                            <div>
                                <p class="font-medium">{{ $product->title_ar ?: $product->title }}</p>
                                <p class="text-xs text-text-muted">{{ $product->category->name_ar ?: $product->category->name }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-5 py-3 text-start font-semibold text-brand">{{ number_format($product->clicks_count) }} نقرة</td>
                </tr>
                @empty
                <tr><td class="px-5 py-6 text-center text-text-muted">لا توجد بيانات نقرات بعد.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="bg-surface-raised border border-border rounded-2xl overflow-hidden">
        <div class="px-5 py-4 border-b border-border font-semibold">أحدث المنتجات المضافة</div>
        <table class="w-full text-sm">
            <tbody>
                @forelse($recentProducts as $product)
                <tr class="border-b border-border last:border-0">
                    <td class="px-5 py-3">
                        <div class="flex items-center gap-3">
                            <img src="{{ $product->image_url }}" class="w-10 h-10 rounded-lg object-cover bg-surface-alt">
                            <div>
                                <p class="font-medium">{{ $product->title_ar ?: $product->title }}</p>
                                <p class="text-xs text-text-muted">{{ $product->category->name_ar ?: $product->category->name }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-5 py-3 text-start font-medium">Rs. {{ number_format($product->price, 0) }}</td>
                </tr>
                @empty
                <tr><td class="px-5 py-6 text-center text-text-muted">لا توجد منتجات بعد.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-layouts.admin>
