@php
    $initialGroups = $product->exists
        ? $product->optionGroups->map(fn ($g) => [
            'name' => $g->name,
            'name_ar' => $g->name_ar,
            'type' => $g->type,
            'is_required' => $g->is_required,
            'options' => $g->options->map(fn ($o) => [
                'label' => $o->label,
                'label_ar' => $o->label_ar,
                'price_delta' => (float) $o->price_delta,
                'is_default' => $o->is_default,
            ])->values()->all(),
        ])->values()->all()
        : [];
    $allBadges = ['Best Seller', 'Fresh', 'Offer'];
    $badgeLabelsAr = ['Best Seller' => 'الأكثر مبيعًا', 'Fresh' => 'طازج', 'Offer' => 'عرض'];
    $selectedBadges = old('badges', $product->badges ?? []);
@endphp

<x-layouts.admin title="{{ $product->exists ? 'تعديل المنتج' : 'منتج جديد' }}">
    <h1 class="text-xl sm:text-2xl font-bold mb-5 sm:mb-6">{{ $product->exists ? 'تعديل المنتج' : 'منتج جديد' }}</h1>

    <form
        method="POST"
        action="{{ $product->exists ? route('admin.products.update', $product) : route('admin.products.store') }}"
        enctype="multipart/form-data"
        class="bg-surface-raised border border-border rounded-2xl p-4 sm:p-6 max-w-3xl space-y-6"
        x-data="{ groups: @js($initialGroups) }"
    >
        @csrf
        @if($product->exists) @method('PUT') @endif

        <div class="grid sm:grid-cols-2 gap-5">
            <div>
                <label class="text-sm font-medium text-text-muted mb-1 block">العنوان (بالعربية)</label>
                <input type="text" name="title_ar" value="{{ old('title_ar', $product->title_ar) }}" required
                    class="w-full rounded-xl border border-border px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand/40">
                @error('title_ar') <p class="text-danger text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            @if($enableEnglish)
            <div>
                <label class="text-sm font-medium text-text-muted mb-1 block">العنوان (بالإنجليزية)</label>
                <input type="text" name="title" value="{{ old('title', $product->title) }}"
                    class="w-full rounded-xl border border-border px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand/40">
                @error('title') <p class="text-danger text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            @endif
        </div>

        <div>
            <label class="text-sm font-medium text-text-muted mb-1 block">القسم</label>
            <select name="category_id" required class="w-full rounded-xl border border-border px-4 py-2.5 text-sm">
                @foreach($categories as $category)
                <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>{{ $category->name_ar ?: $category->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="grid sm:grid-cols-2 gap-5">
            <div>
                <label class="text-sm font-medium text-text-muted mb-1 block">الوصف (بالعربية)</label>
                <textarea name="description_ar" rows="3" class="w-full rounded-xl border border-border px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand/40">{{ old('description_ar', $product->description_ar) }}</textarea>
            </div>
            @if($enableEnglish)
            <div>
                <label class="text-sm font-medium text-text-muted mb-1 block">الوصف (بالإنجليزية)</label>
                <textarea name="description" rows="3" class="w-full rounded-xl border border-border px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand/40">{{ old('description', $product->description) }}</textarea>
            </div>
            @endif
        </div>

        <div class="grid sm:grid-cols-2 gap-5">
            <div>
                <label class="text-sm font-medium text-text-muted mb-1 block">السعر</label>
                <input type="number" step="0.01" name="price" value="{{ old('price', $product->price) }}" required
                    class="w-full rounded-xl border border-border px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand/40">
            </div>
            <div>
                <label class="text-sm font-medium text-text-muted mb-1 block">سعر المقارنة (اختياري)</label>
                <input type="number" step="0.01" name="compare_price" value="{{ old('compare_price', $product->compare_price) }}"
                    class="w-full rounded-xl border border-border px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand/40">
            </div>
        </div>

        <div>
            <label class="text-sm font-medium text-text-muted mb-1 block">الصورة</label>
            @if($product->image)
            <img src="{{ $product->image_url }}" class="w-20 h-20 rounded-xl object-cover mb-2">
            @endif
            <input type="file" name="image" accept="image/*" class="w-full text-sm">
        </div>

        <div>
            <label class="text-sm font-medium text-text-muted mb-2 block">الشارات</label>
            <div class="flex flex-wrap gap-x-4 gap-y-2">
                @foreach($allBadges as $badge)
                <label class="flex items-center gap-2 text-sm">
                    <input type="checkbox" name="badges[]" value="{{ $badge }}" {{ in_array($badge, $selectedBadges) ? 'checked' : '' }} class="rounded border-border text-brand focus:ring-brand/40">
                    {{ $badgeLabelsAr[$badge] }}
                </label>
                @endforeach
            </div>
        </div>

        <label class="flex items-center gap-2">
            <input type="checkbox" name="is_available" value="1" {{ old('is_available', $product->id ? $product->is_available : true) ? 'checked' : '' }} class="rounded border-border text-brand focus:ring-brand/40">
            <span class="text-sm">متوفر</span>
        </label>

        {{-- OPTION GROUPS --}}
        <div class="border-t border-border pt-5">
            <div class="flex items-center justify-between mb-3">
                <label class="text-sm font-semibold">الخيارات والإضافات</label>
                <button type="button" @click="groups.push({ name: '', name_ar: '', type: 'single', is_required: false, options: [] })"
                    class="text-xs font-semibold text-brand flex items-center gap-1">
                    <i data-lucide="plus" class="w-3.5 h-3.5"></i> إضافة مجموعة
                </button>
            </div>

            <template x-for="(group, gi) in groups" :key="gi">
                <div class="border border-border rounded-xl p-4 mb-3 space-y-3">
                    <div class="flex gap-3 items-start flex-wrap">
                        <input type="text" :name="'groups[' + gi + '][name_ar]'" x-model="group.name_ar" placeholder="اسم المجموعة بالعربية"
                            class="flex-1 min-w-[140px] rounded-lg border border-border px-3 py-2 text-sm">
                        @if($enableEnglish)
                        <input type="text" :name="'groups[' + gi + '][name]'" x-model="group.name" placeholder="اسم المجموعة بالإنجليزية (Size)"
                            class="flex-1 min-w-[140px] rounded-lg border border-border px-3 py-2 text-sm">
                        @endif
                        <select :name="'groups[' + gi + '][type]'" x-model="group.type" class="rounded-lg border border-border px-3 py-2 text-sm">
                            <option value="single">اختيار واحد</option>
                            <option value="multiple">اختيار متعدد</option>
                        </select>
                        <label class="flex items-center gap-1.5 text-xs whitespace-nowrap pt-2.5">
                            <input type="checkbox" :name="'groups[' + gi + '][is_required]'" value="1" x-model="group.is_required" class="rounded border-border text-brand">
                            مطلوب
                        </label>
                        <button type="button" @click="groups.splice(gi, 1)" class="text-text-muted hover:text-danger pt-2">
                            <i data-lucide="x" class="w-4 h-4"></i>
                        </button>
                    </div>

                    <div class="ps-2 space-y-2">
                        <template x-for="(option, oi) in group.options" :key="oi">
                            <div class="flex gap-2 items-center flex-wrap">
                                <input type="text" :name="'groups[' + gi + '][options][' + oi + '][label_ar]'" x-model="option.label_ar" placeholder="الخيار بالعربية"
                                    class="flex-1 min-w-[120px] rounded-lg border border-border px-3 py-1.5 text-sm">
                                @if($enableEnglish)
                                <input type="text" :name="'groups[' + gi + '][options][' + oi + '][label]'" x-model="option.label" placeholder="الخيار بالإنجليزية"
                                    class="flex-1 min-w-[120px] rounded-lg border border-border px-3 py-1.5 text-sm">
                                @endif
                                <input type="number" step="0.01" :name="'groups[' + gi + '][options][' + oi + '][price_delta]'" x-model="option.price_delta" placeholder="+السعر"
                                    class="w-24 rounded-lg border border-border px-3 py-1.5 text-sm">
                                <label class="flex items-center gap-1 text-xs whitespace-nowrap">
                                    <input type="checkbox" :name="'groups[' + gi + '][options][' + oi + '][is_default]'" value="1" x-model="option.is_default" class="rounded border-border text-brand">
                                    افتراضي
                                </label>
                                <button type="button" @click="group.options.splice(oi, 1)" class="text-text-muted hover:text-danger">
                                    <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                </button>
                            </div>
                        </template>
                        <button type="button" @click="group.options.push({ label: '', label_ar: '', price_delta: 0, is_default: false })"
                            class="text-xs text-brand font-medium flex items-center gap-1">
                            <i data-lucide="plus" class="w-3 h-3"></i> إضافة خيار
                        </button>
                    </div>
                </div>
            </template>
        </div>

        <div class="flex items-center gap-3 pt-2">
            <button class="flex-1 sm:flex-none bg-brand hover:bg-brand-dark text-white font-semibold px-5 py-3 sm:py-2.5 rounded-xl">حفظ</button>
            <a href="{{ route('admin.products.index') }}" class="text-text-muted text-sm px-3 py-3">إلغاء</a>
        </div>
    </form>
</x-layouts.admin>
