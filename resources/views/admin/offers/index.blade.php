<x-layouts.admin title="العروض">
    <div class="flex flex-wrap items-center justify-between gap-3 mb-5 sm:mb-6">
        <h1 class="text-xl sm:text-2xl font-bold">العروض واللافتات</h1>
        <a href="{{ route('admin.offers.create') }}" class="bg-brand hover:bg-brand-dark text-white text-sm font-semibold px-4 py-2.5 rounded-xl flex items-center gap-2">
            <i data-lucide="plus" class="w-4 h-4"></i> عرض جديد
        </a>
    </div>

    <div class="bg-surface-raised border border-border rounded-2xl overflow-hidden">
      <div class="overflow-x-auto">
        <table class="w-full text-sm min-w-[640px]">
            <thead class="bg-surface-alt text-text-muted text-xs uppercase">
                <tr>
                    <th class="text-start px-5 py-3">العرض</th>
                    <th class="text-start px-5 py-3">المنتج المرتبط</th>
                    <th class="text-start px-5 py-3">السعر</th>
                    <th class="text-start px-5 py-3">الحالة</th>
                    <th class="px-5 py-3"></th>
                </tr>
            </thead>
            <tbody>
                @forelse($offers as $offer)
                <tr class="border-t border-border">
                    <td class="px-5 py-3">
                        <p class="font-medium">{{ $offer->title_ar ?: $offer->title }}</p>
                        <p class="text-xs text-text-muted">{{ $offer->subtitle_ar ?: $offer->subtitle }}</p>
                    </td>
                    <td class="px-5 py-3 text-text-muted">{{ $offer->product?->title_ar ?? $offer->product?->title ?? '—' }}</td>
                    <td class="px-5 py-3 font-medium">{{ $offer->discount_price ? 'Rs. ' . number_format($offer->discount_price, 0) : '—' }}</td>
                    <td class="px-5 py-3">
                        <span @class([
                            'text-xs font-semibold px-2 py-1 rounded-full',
                            'bg-brand/10 text-brand' => $offer->is_active,
                            'bg-text-muted/10 text-text-muted' => !$offer->is_active,
                        ])>{{ $offer->is_active ? 'نشط' : 'مخفي' }}</span>
                    </td>
                    <td class="px-5 py-3 text-end">
                        <div class="flex items-center gap-3 justify-end">
                            <a href="{{ route('admin.offers.edit', $offer) }}" class="text-text-muted hover:text-brand">
                                <i data-lucide="pencil" class="w-4 h-4"></i>
                            </a>
                            <form method="POST" action="{{ route('admin.offers.destroy', $offer) }}" onsubmit="return confirm('حذف هذا العرض؟')">
                                @csrf @method('DELETE')
                                <button class="text-text-muted hover:text-danger">
                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="px-5 py-8 text-center text-text-muted">لا توجد عروض بعد.</td></tr>
                @endforelse
            </tbody>
        </table>
      </div>
    </div>
</x-layouts.admin>
