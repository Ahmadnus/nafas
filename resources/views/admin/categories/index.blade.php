<x-layouts.admin title="الأقسام">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold">الأقسام</h1>
        <a href="{{ route('admin.categories.create') }}" class="bg-brand hover:bg-brand-dark text-white text-sm font-semibold px-4 py-2.5 rounded-xl flex items-center gap-2">
            <i data-lucide="plus" class="w-4 h-4"></i> قسم جديد
        </a>
    </div>

    <div class="bg-surface-raised border border-border rounded-2xl overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-surface-alt text-text-muted text-xs uppercase">
                <tr>
                    <th class="text-start px-5 py-3">الاسم</th>
                    <th class="text-start px-5 py-3">المنتجات</th>
                    <th class="text-start px-5 py-3">الترتيب</th>
                    <th class="text-start px-5 py-3">الحالة</th>
                    <th class="px-5 py-3"></th>
                </tr>
            </thead>
            <tbody>
                @forelse($categories as $category)
                <tr class="border-t border-border">
                    <td class="px-5 py-3 flex items-center gap-2 font-medium">
                        <i data-lucide="{{ $category->icon ?? 'circle' }}" class="w-4 h-4 text-brand"></i>
                        {{ $category->name_ar ?: $category->name }}
                    </td>
                    <td class="px-5 py-3">{{ $category->products_count }}</td>
                    <td class="px-5 py-3">{{ $category->sort_order }}</td>
                    <td class="px-5 py-3">
                        <span @class([
                            'text-xs font-semibold px-2 py-1 rounded-full',
                            'bg-brand/10 text-brand' => $category->is_active,
                            'bg-text-muted/10 text-text-muted' => !$category->is_active,
                        ])>{{ $category->is_active ? 'نشط' : 'مخفي' }}</span>
                    </td>
                    <td class="px-5 py-3 text-end">
                        <div class="flex items-center gap-3 justify-end">
                            <a href="{{ route('admin.categories.edit', $category) }}" class="text-text-muted hover:text-brand">
                                <i data-lucide="pencil" class="w-4 h-4"></i>
                            </a>
                            <form method="POST" action="{{ route('admin.categories.destroy', $category) }}" onsubmit="return confirm('حذف هذا القسم؟')">
                                @csrf @method('DELETE')
                                <button class="text-text-muted hover:text-danger">
                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="px-5 py-8 text-center text-text-muted">لا توجد أقسام بعد.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-layouts.admin>
