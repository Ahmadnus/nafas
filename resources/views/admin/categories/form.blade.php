<x-layouts.admin title="{{ $category->exists ? 'تعديل القسم' : 'قسم جديد' }}">
    <h1 class="text-2xl font-bold mb-6">{{ $category->exists ? 'تعديل القسم' : 'قسم جديد' }}</h1>

    <form
        method="POST"
        action="{{ $category->exists ? route('admin.categories.update', $category) : route('admin.categories.store') }}"
        class="bg-surface-raised border border-border rounded-2xl p-6 max-w-xl space-y-5"
    >
        @csrf
        @if($category->exists) @method('PUT') @endif

        <div>
            <label class="text-sm font-medium text-text-muted mb-1 block">الاسم (بالعربية)</label>
            <input type="text" name="name_ar" value="{{ old('name_ar', $category->name_ar) }}" required
                class="w-full rounded-xl border border-border px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand/40">
            @error('name_ar') <p class="text-danger text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        @if($enableEnglish)
        <div>
            <label class="text-sm font-medium text-text-muted mb-1 block">الاسم (بالإنجليزية)</label>
            <input type="text" name="name" value="{{ old('name', $category->name) }}"
                class="w-full rounded-xl border border-border px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand/40">
        </div>
        @endif

        <div>
            <label class="text-sm font-medium text-text-muted mb-1 block">اسم أيقونة Lucide</label>
            <input type="text" name="icon" value="{{ old('icon', $category->icon) }}" placeholder="مثال: cup-soda, milk, apple"
                class="w-full rounded-xl border border-border px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand/40">
            <p class="text-xs text-text-muted mt-1">تصفح أسماء الأيقونات على lucide.dev</p>
        </div>

        <div>
            <label class="text-sm font-medium text-text-muted mb-1 block">ترتيب العرض</label>
            <input type="number" name="sort_order" value="{{ old('sort_order', $category->sort_order ?? 0) }}"
                class="w-full rounded-xl border border-border px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand/40">
        </div>

        <label class="flex items-center gap-2">
            <input type="checkbox" name="is_active" value="1" {{ old('is_active', $category->is_active ?? true) ? 'checked' : '' }} class="rounded border-border text-brand focus:ring-brand/40">
            <span class="text-sm">نشط (يظهر في القائمة)</span>
        </label>

        <div class="flex items-center gap-3 pt-2">
            <button class="bg-brand hover:bg-brand-dark text-white font-semibold px-5 py-2.5 rounded-xl">حفظ</button>
            <a href="{{ route('admin.categories.index') }}" class="text-text-muted text-sm">إلغاء</a>
        </div>
    </form>
</x-layouts.admin>
