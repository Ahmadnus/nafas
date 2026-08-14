<x-layouts.admin title="{{ $offer->exists ? 'تعديل العرض' : 'عرض جديد' }}">
    <h1 class="text-2xl font-bold mb-6">{{ $offer->exists ? 'تعديل العرض' : 'عرض جديد' }}</h1>

    <form
        method="POST"
        action="{{ $offer->exists ? route('admin.offers.update', $offer) : route('admin.offers.store') }}"
        enctype="multipart/form-data"
        class="bg-surface-raised border border-border rounded-2xl p-6 max-w-xl space-y-5"
    >
        @csrf
        @if($offer->exists) @method('PUT') @endif

        <div class="grid sm:grid-cols-2 gap-5">
            <div>
                <label class="text-sm font-medium text-text-muted mb-1 block">العنوان (بالعربية)</label>
                <input type="text" name="title_ar" value="{{ old('title_ar', $offer->title_ar) }}" required
                    class="w-full rounded-xl border border-border px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand/40">
            </div>
            @if($enableEnglish)
            <div>
                <label class="text-sm font-medium text-text-muted mb-1 block">العنوان (بالإنجليزية)</label>
                <input type="text" name="title" value="{{ old('title', $offer->title) }}"
                    class="w-full rounded-xl border border-border px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand/40">
            </div>
            @endif
        </div>

        <div class="grid sm:grid-cols-2 gap-5">
            <div>
                <label class="text-sm font-medium text-text-muted mb-1 block">العنوان الفرعي (بالعربية)</label>
                <input type="text" name="subtitle_ar" value="{{ old('subtitle_ar', $offer->subtitle_ar) }}"
                    class="w-full rounded-xl border border-border px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand/40">
            </div>
            @if($enableEnglish)
            <div>
                <label class="text-sm font-medium text-text-muted mb-1 block">العنوان الفرعي (بالإنجليزية)</label>
                <input type="text" name="subtitle" value="{{ old('subtitle', $offer->subtitle) }}"
                    class="w-full rounded-xl border border-border px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand/40">
            </div>
            @endif
        </div>

        <div>
            <label class="text-sm font-medium text-text-muted mb-1 block">المنتج المرتبط (اختياري)</label>
            <select name="product_id" class="w-full rounded-xl border border-border px-4 py-2.5 text-sm">
                <option value="">— بدون —</option>
                @foreach($products as $product)
                <option value="{{ $product->id }}" {{ old('product_id', $offer->product_id) == $product->id ? 'selected' : '' }}>{{ $product->title_ar ?: $product->title }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="text-sm font-medium text-text-muted mb-1 block">سعر الخصم (اختياري)</label>
            <input type="number" step="0.01" name="discount_price" value="{{ old('discount_price', $offer->discount_price) }}"
                class="w-full rounded-xl border border-border px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand/40">
        </div>

        <div class="grid sm:grid-cols-2 gap-5">
            <div>
                <label class="text-sm font-medium text-text-muted mb-1 block">تاريخ البدء</label>
                <input type="date" name="starts_at" value="{{ old('starts_at', $offer->starts_at?->format('Y-m-d')) }}"
                    class="w-full rounded-xl border border-border px-4 py-2.5 text-sm">
            </div>
            <div>
                <label class="text-sm font-medium text-text-muted mb-1 block">تاريخ الانتهاء</label>
                <input type="date" name="ends_at" value="{{ old('ends_at', $offer->ends_at?->format('Y-m-d')) }}"
                    class="w-full rounded-xl border border-border px-4 py-2.5 text-sm">
            </div>
        </div>

        <div>
            <label class="text-sm font-medium text-text-muted mb-1 block">صورة اللافتة</label>
            @if($offer->image)
            <img src="{{ $offer->imageUrl() }}" class="w-20 h-20 rounded-xl object-cover mb-2">
            @endif
            <input type="file" name="image" accept="image/*" class="w-full text-sm">
        </div>

        <label class="flex items-center gap-2">
            <input type="checkbox" name="is_active" value="1" {{ old('is_active', $offer->id ? $offer->is_active : true) ? 'checked' : '' }} class="rounded border-border text-brand focus:ring-brand/40">
            <span class="text-sm">نشط</span>
        </label>

        <div class="flex items-center gap-3 pt-2">
            <button class="bg-brand hover:bg-brand-dark text-white font-semibold px-5 py-2.5 rounded-xl">حفظ</button>
            <a href="{{ route('admin.offers.index') }}" class="text-text-muted text-sm">إلغاء</a>
        </div>
    </form>
</x-layouts.admin>
