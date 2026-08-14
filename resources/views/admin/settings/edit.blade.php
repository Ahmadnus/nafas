<x-layouts.admin title="الإعدادات">
    <h1 class="text-2xl font-bold mb-6">الإعدادات</h1>

    <form method="POST" action="{{ route('admin.settings.update') }}" class="bg-surface-raised border border-border rounded-2xl p-6 max-w-xl space-y-5">
        @csrf
        @method('PUT')

        <div class="grid sm:grid-cols-2 gap-5">
            <div>
                <label class="text-sm font-medium text-text-muted mb-1 block">اسم المطعم (بالإنجليزية)</label>
                <input type="text" name="restaurant_name" value="{{ old('restaurant_name', $settings['restaurant_name']) }}" required
                    class="w-full rounded-xl border border-border px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand/40">
            </div>
            <div>
                <label class="text-sm font-medium text-text-muted mb-1 block">اسم المطعم (بالعربية)</label>
                <input type="text" name="restaurant_name_ar" value="{{ old('restaurant_name_ar', $settings['restaurant_name_ar']) }}"
                    class="w-full rounded-xl border border-border px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand/40">
            </div>
        </div>

        <div class="grid sm:grid-cols-2 gap-5">
            <div>
                <label class="text-sm font-medium text-text-muted mb-1 block">الشعار (بالإنجليزية)</label>
                <input type="text" name="tagline" value="{{ old('tagline', $settings['tagline']) }}"
                    class="w-full rounded-xl border border-border px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand/40">
            </div>
            <div>
                <label class="text-sm font-medium text-text-muted mb-1 block">الشعار (بالعربية)</label>
                <input type="text" name="tagline_ar" value="{{ old('tagline_ar', $settings['tagline_ar']) }}"
                    class="w-full rounded-xl border border-border px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand/40">
            </div>
        </div>

        <div>
            <label class="text-sm font-medium text-text-muted mb-1 block">رقم واتساب</label>
            <input type="text" name="whatsapp_number" value="{{ old('whatsapp_number', $settings['whatsapp_number']) }}" required
                placeholder="مثال: 923001234567 (رمز الدولة، بدون + أو مسافات)"
                class="w-full rounded-xl border border-border px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand/40">
            <p class="text-xs text-text-muted mt-1">يُستخدم لفتح محادثة واتساب مع طلبات العملاء.</p>
        </div>

        <div>
            <label class="text-sm font-medium text-text-muted mb-1 block">رمز العملة</label>
            <input type="text" name="currency_symbol" value="{{ old('currency_symbol', $settings['currency_symbol']) }}" required
                class="w-full rounded-xl border border-border px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand/40">
        </div>

        <label class="flex items-center gap-2">
            <input type="checkbox" name="is_open" value="1" {{ old('is_open', $settings['is_open']) === '1' ? 'checked' : '' }} class="rounded border-border text-brand focus:ring-brand/40">
            <span class="text-sm">المطعم مفتوح حاليًا</span>
        </label>

        <div class="border-t border-border pt-5">
            <label class="flex items-center gap-2">
                <input type="checkbox" name="enable_english" value="1" {{ old('enable_english', $settings['enable_english'] ?? '1') === '1' ? 'checked' : '' }} class="rounded border-border text-brand focus:ring-brand/40">
                <span class="text-sm font-medium">تفعيل اللغة الإنجليزية للمنيو</span>
            </label>
            <p class="text-xs text-text-muted mt-1">عند التعطيل، سيتم إخفاء زر تبديل اللغة وحقول الترجمة الإنجليزية من نماذج المنتجات والأقسام والعروض.</p>
        </div>

        <div class="border-t border-border pt-5 space-y-4">
            <h2 class="text-sm font-semibold">روابط التواصل الاجتماعي والموقع</h2>

            <div class="grid sm:grid-cols-2 gap-5">
                <div>
                    <label class="text-sm font-medium text-text-muted mb-1 block">إنستغرام</label>
                    <input type="text" name="instagram_url" value="{{ old('instagram_url', $settings['instagram_url'] ?? '') }}" placeholder="https://instagram.com/..."
                        class="w-full rounded-xl border border-border px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand/40">
                </div>
                <div>
                    <label class="text-sm font-medium text-text-muted mb-1 block">فيسبوك</label>
                    <input type="text" name="facebook_url" value="{{ old('facebook_url', $settings['facebook_url'] ?? '') }}" placeholder="https://facebook.com/..."
                        class="w-full rounded-xl border border-border px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand/40">
                </div>
                <div>
                    <label class="text-sm font-medium text-text-muted mb-1 block">تيك توك</label>
                    <input type="text" name="tiktok_url" value="{{ old('tiktok_url', $settings['tiktok_url'] ?? '') }}" placeholder="https://tiktok.com/@..."
                        class="w-full rounded-xl border border-border px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand/40">
                </div>
                <div>
                    <label class="text-sm font-medium text-text-muted mb-1 block">الموقع على الخريطة (Google Maps)</label>
                    <input type="text" name="location_url" value="{{ old('location_url', $settings['location_url'] ?? '') }}" placeholder="https://maps.google.com/..."
                        class="w-full rounded-xl border border-border px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand/40">
                </div>
            </div>
        </div>

        <div class="flex items-center gap-3 pt-2">
            <button class="bg-brand hover:bg-brand-dark text-white font-semibold px-5 py-2.5 rounded-xl">حفظ الإعدادات</button>
        </div>
    </form>
</x-layouts.admin>
