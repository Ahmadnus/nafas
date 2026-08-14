<x-layouts.app :settings="$settings">
    <div
        x-data="{
            activeCategory: {{ $categories->first()?->id ?? 'null' }},
            langBarVisible: true,
            lastScrollY: 0,
            setActive(id) { this.activeCategory = id },
            scrollToCategory(id) {
                this.activeCategory = id;
                const el = document.getElementById('cat-' + id);
                if (el) {
                    const offset = 120;
                    const top = el.getBoundingClientRect().top + window.scrollY - offset;
                    window.scrollTo({ top, behavior: 'smooth' });
                }
            },
        }"
        @scroll.window.debounce.100ms="
            let closest = null, closestDist = Infinity;
            document.querySelectorAll('[data-category-section]').forEach(el => {
                const dist = Math.abs(el.getBoundingClientRect().top - 140);
                if (dist < closestDist) { closestDist = dist; closest = el; }
            });
            if (closest) activeCategory = Number(closest.dataset.categoryId);

            const y = window.scrollY;
            langBarVisible = y < 80 || y < lastScrollY;
            lastScrollY = y;
        "
        :dir="$store.lang.dir"
    >
        {{-- LANGUAGE SWITCHER --}}
        @if($settings['enable_english'])
        <button
            @click="$store.lang.toggle()"
            x-show="langBarVisible"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 -translate-y-3"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 -translate-y-3"
            class="fixed top-3 start-3 z-40 bg-surface-raised/90 backdrop-blur border border-border shadow-card rounded-full px-3 py-1.5 text-xs font-semibold flex items-center gap-1.5"
        >
            <i data-lucide="languages" class="w-3.5 h-3.5"></i>
            <span x-text="$store.lang.current === 'ar' ? 'EN' : 'AR'"></span>
        </button>
        @endif

        {{-- HERO --}}
        <header class="relative overflow-hidden bg-gradient-to-br from-slate-700 to-slate-900 text-text-inverse">
            <div class="absolute inset-0 opacity-10" style="background-image:radial-gradient(circle at 20% 20%, white 2px, transparent 2px);background-size:28px 28px;"></div>
            <div class="absolute -end-10 -top-10 w-44 h-44 rounded-full overflow-hidden opacity-20 rotate-12 pointer-events-none select-none">
                <img src="{{ asset('images/products/juice.svg') }}" alt="" class="w-full h-full object-cover">
            </div>
            <div class="absolute -start-10 -bottom-14 w-52 h-52 rounded-full overflow-hidden opacity-20 -rotate-12 pointer-events-none select-none">
                <img src="{{ asset('images/products/smoothie.svg') }}" alt="" class="w-full h-full object-cover">
            </div>
            <div class="relative max-w-5xl mx-auto px-6 py-16 text-center">
                <img data-hero-logo src="{{ asset('images/brand/nafas-logo.png') }}" alt="نفس" class="w-48 h-48 sm:w-64 sm:h-64 object-contain mx-auto mb-2 drop-shadow-2xl">
                <p data-hero-eyebrow class="uppercase tracking-widest text-sm font-medium text-white/80 mb-3" x-text="$store.lang.t('digital_menu')"></p>
                <h1 data-hero-title class="sr-only" x-text="$store.lang.pick(@js($settings['restaurant_name_ar']), @js($settings['restaurant_name']))"></h1>
                <p data-hero-sub class="text-white/90 text-lg mb-6" x-text="$store.lang.pick(@js($settings['tagline_ar']), @js($settings['tagline']))"></p>
                <div data-hero-cta class="inline-flex items-center gap-2 bg-white/15 backdrop-blur px-4 py-2 rounded-full text-sm">
                    <i data-lucide="{{ $settings['is_open'] ? 'check-circle-2' : 'clock' }}" class="w-4 h-4"></i>
                    <span x-text="{{ $settings['is_open'] ? 'true' : 'false' }} ? $store.lang.t('open_now') : $store.lang.t('closed_now')"></span>
                </div>
            </div>
        </header>

        {{-- OFFERS BILLBOARD --}}
        @if($offers->count())
        <section data-offer-billboard class="max-w-6xl mx-auto px-4 -mt-8 relative z-10">
            <div
                x-data="{
                    slides: {{ $offers->count() }},
                    active: 0,
                    timer: null,
                    start() {
                        this.timer = setInterval(() => this.next(), 4500);
                    },
                    next() {
                        const from = this.active;
                        this.active = (this.active + 1) % this.slides;
                        this.animate(from, this.active);
                    },
                    goTo(i) {
                        if (i === this.active) return;
                        clearInterval(this.timer);
                        const from = this.active;
                        this.active = i;
                        this.animate(from, this.active);
                        this.start();
                    },
                    animate(from, to) {
                        const slides = $refs.track.children;
                        gsap.to(slides[from], { opacity: 0, scale: 0.97, duration: 0.4, ease: 'power2.inOut' });
                        gsap.fromTo(slides[to], { opacity: 0, scale: 1.03 }, { opacity: 1, scale: 1, duration: 0.5, ease: 'power2.out' });
                    },
                }"
                x-init="start()"
                class="relative rounded-2xl sm:rounded-3xl overflow-hidden shadow-float border border-border"
            >
                <div x-ref="track" class="relative h-40 sm:h-56">
                    @foreach($offers as $i => $offer)
                    <div
                        class="offer-card absolute inset-0 flex items-center gap-4 sm:gap-6 px-5 sm:px-10 bg-gradient-to-br from-accent-3 to-accent-3-dark text-white cursor-pointer active:scale-[0.98] transition-transform"
                        style="{{ $i === 0 ? '' : 'opacity:0' }}"
                        @click="$store.cart.addOffer(@js($offer), @js($offer->product))"
                    >
                        <div @class(['flex-1 min-w-0', 'text-center mx-auto' => !$offer->hasRealImage()])>
                            <span class="inline-block text-[10px] sm:text-xs font-bold uppercase tracking-widest bg-accent-2 px-2.5 py-1 rounded-full mb-2 sm:mb-3" x-text="$store.lang.t('offer')"></span>
                            <p class="font-heading text-lg sm:text-3xl font-bold truncate" x-text="$store.lang.pick(@js($offer->title_ar), @js($offer->title))"></p>
                            <p class="text-xs sm:text-base text-white/85 truncate mt-1" x-text="$store.lang.pick(@js($offer->subtitle_ar), @js($offer->subtitle))"></p>
                            @if($offer->discount_price)
                            <p class="text-brand-light font-extrabold text-lg sm:text-2xl mt-2 sm:mt-3" x-text="$store.lang.currency + '{{ number_format($offer->discount_price, 0) }}'"></p>
                            @endif
                        </div>
                        @if($offer->hasRealImage())
                        <img src="{{ $offer->imageUrl() }}" alt="{{ $offer->title }}" class="w-20 h-20 sm:w-36 sm:h-36 rounded-2xl object-cover shrink-0 shadow-float hidden xs:block">
                        @else
                        <i data-lucide="sparkles" class="w-14 h-14 sm:w-20 sm:h-20 text-white/20 shrink-0 hidden sm:block"></i>
                        @endif
                    </div>
                    @endforeach
                </div>

                @if($offers->count() > 1)
                <div class="absolute bottom-3 sm:bottom-4 inset-x-0 flex items-center justify-center gap-1.5">
                    @foreach($offers as $i => $offer)
                    <button
                        @click="goTo({{ $i }})"
                        :class="active === {{ $i }} ? 'w-6 bg-white' : 'w-1.5 bg-white/50'"
                        class="h-1.5 rounded-full transition-all duration-300"
                    ></button>
                    @endforeach
                </div>
                @endif
            </div>
        </section>
        @endif

        {{-- STICKY CATEGORY TABS --}}
        <nav class="sticky top-0 z-30 bg-surface/90 backdrop-blur border-b border-border mt-6">
            <div class="max-w-6xl mx-auto px-4">
                <div class="flex gap-2 overflow-x-auto no-scrollbar py-3">
                    @foreach($categories as $category)
                    <button
                        @click="scrollToCategory({{ $category->id }})"
                        :class="activeCategory === {{ $category->id }}
                            ? 'bg-brand text-text-inverse shadow-card'
                            : 'bg-surface-alt text-text-muted hover:bg-border/50'"
                        class="flex items-center gap-2 px-4 py-2 rounded-full text-sm font-medium whitespace-nowrap transition-all duration-200 active:scale-95"
                    >
                        <i data-lucide="{{ $category->icon ?? 'circle' }}" class="w-4 h-4"></i>
                        <span x-text="$store.lang.pick(@js($category->name_ar), @js($category->name))"></span>
                    </button>
                    @endforeach
                </div>
            </div>
        </nav>

        {{-- CATEGORY SECTIONS --}}
        <main class="max-w-6xl mx-auto px-4 py-10 space-y-14">
            @foreach($categories as $category)
            <section id="cat-{{ $category->id }}" data-category-section data-category-id="{{ $category->id }}">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 rounded-xl bg-brand/10 text-brand flex items-center justify-center">
                        <i data-lucide="{{ $category->icon ?? 'circle' }}" class="w-5 h-5"></i>
                    </div>
                    <h2 class="font-heading text-2xl font-bold text-text" x-text="$store.lang.pick(@js($category->name_ar), @js($category->name))"></h2>
                </div>

                @if($category->products->isEmpty())
                    <p class="text-text-muted text-sm" x-text="$store.lang.t('no_items')"></p>
                @else
                <div
                    class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-3 gap-3 sm:gap-5 product-grid"
                    x-intersect.once="window.gsapAnimations.staggerIn('#cat-{{ $category->id }} .product-card')"
                >
                    @foreach($category->products as $product)
                    <x-product-card :product="$product" :currency="$settings['currency_symbol']" :is-popular="$popularProductIds->contains($product->id)" />
                    @endforeach
                </div>
                @endif
            </section>
            @endforeach
        </main>

        <x-product-modal :currency="$settings['currency_symbol']" />
        <x-cart-drawer :currency="$settings['currency_symbol']" />
        <x-floating-cart-button :currency="$settings['currency_symbol']" />

        <footer class="text-center text-sm text-text-muted py-10 border-t border-border space-y-3">
            @if($settings['instagram_url'] || $settings['facebook_url'] || $settings['tiktok_url'] || $settings['location_url'])
            <div class="flex items-center justify-center gap-3">
                @if($settings['instagram_url'])
                <a href="{{ $settings['instagram_url'] }}" target="_blank" rel="noopener" class="w-9 h-9 rounded-full bg-surface-alt hover:bg-brand hover:text-white flex items-center justify-center transition-colors">
                    <i data-lucide="instagram" class="w-4 h-4"></i>
                </a>
                @endif
                @if($settings['facebook_url'])
                <a href="{{ $settings['facebook_url'] }}" target="_blank" rel="noopener" class="w-9 h-9 rounded-full bg-surface-alt hover:bg-brand hover:text-white flex items-center justify-center transition-colors">
                    <i data-lucide="facebook" class="w-4 h-4"></i>
                </a>
                @endif
                @if($settings['tiktok_url'])
                <a href="{{ $settings['tiktok_url'] }}" target="_blank" rel="noopener" class="w-9 h-9 rounded-full bg-surface-alt hover:bg-brand hover:text-white flex items-center justify-center transition-colors">
                    <i data-lucide="music-2" class="w-4 h-4"></i>
                </a>
                @endif
                @if($settings['location_url'])
                <a href="{{ $settings['location_url'] }}" target="_blank" rel="noopener" class="w-9 h-9 rounded-full bg-surface-alt hover:bg-brand hover:text-white flex items-center justify-center transition-colors">
                    <i data-lucide="map-pin" class="w-4 h-4"></i>
                </a>
                @endif
            </div>
            @endif
            <p>&copy; {{ date('Y') }} <span x-text="$store.lang.pick(@js($settings['restaurant_name_ar']), @js($settings['restaurant_name']))"></span></p>
            <p class="text-xs opacity-70" x-text="$store.lang.current === 'ar' ? 'بواسطة Focus' : 'By Focus'"></p>
        </footer>
    </div>
</x-layouts.app>
