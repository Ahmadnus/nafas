@props(['product', 'currency', 'isPopular' => false])

<div
    class="product-card group bg-surface-raised border border-border rounded-xl sm:rounded-2xl overflow-hidden shadow-card hover:shadow-float hover:-translate-y-1 transition-[box-shadow,transform] duration-300 cursor-pointer"
    @click="window.trackProductClick({{ $product->id }}); $store.modal.show(@js($product))"
>
    <div class="relative aspect-[4/3] overflow-hidden bg-surface-alt">
        <img
            src="{{ $product->image_url }}"
            alt="{{ $product->title }}"
            loading="lazy"
            class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500"
        >
        @if(!empty($product->badges) || $isPopular)
        <div class="absolute top-1.5 start-1.5 sm:top-3 sm:start-3 flex flex-wrap gap-1 sm:gap-1.5">
            @if($isPopular)
            <span class="text-[10px] sm:text-xs font-semibold px-1.5 sm:px-2.5 py-0.5 sm:py-1 rounded-full shadow-sm backdrop-blur bg-accent-3 text-white flex items-center gap-0.5">
                <i data-lucide="flame" class="w-2.5 h-2.5 sm:w-3 sm:h-3"></i>
                <span x-text="$store.lang.current === 'ar' ? 'الأكثر طلباً' : 'Popular'"></span>
            </span>
            @endif
            @foreach($product->badges as $badge)
            <span @class([
                'text-[10px] sm:text-xs font-semibold px-1.5 sm:px-2.5 py-0.5 sm:py-1 rounded-full shadow-sm backdrop-blur',
                'bg-accent-2 text-white' => $badge === 'Best Seller',
                'bg-brand text-white' => $badge === 'Fresh',
                'bg-danger text-white' => $badge === 'Offer',
            ]) x-text="$store.lang.t('{{ $badge === 'Best Seller' ? 'best_seller' : ($badge === 'Fresh' ? 'fresh' : 'offer') }}')"></span>
            @endforeach
        </div>
        @endif

        @unless($product->is_available)
        <div class="absolute inset-0 bg-black/50 flex items-center justify-center">
            <span class="text-white font-semibold text-xs sm:text-sm" x-text="$store.lang.t('sold_out')"></span>
        </div>
        @endunless
    </div>

    <div class="p-2.5 sm:p-4">
        <h3 class="font-heading font-semibold text-text text-sm sm:text-base mb-0.5 sm:mb-1 truncate" x-text="$store.lang.pick(@js($product->title_ar), @js($product->title))"></h3>
        <p class="hidden sm:block text-sm text-text-muted line-clamp-2 mb-3 min-h-[2.5rem]" x-text="$store.lang.pick(@js($product->description_ar), @js($product->description))"></p>
        <div class="flex items-center justify-between gap-1">
            <span class="font-bold text-brand text-sm sm:text-base" x-text="$store.lang.currency + '{{ number_format($product->price, 0) }}'"></span>
            <button
                type="button"
                @click.stop="
                    window.trackProductClick({{ $product->id }});
                    window.gsapAnimations.flyToCart($el.closest('.product-card').querySelector('img'), document.querySelector('[data-cart-badge]') || $el);
                    @if($product->optionGroups->isEmpty())
                    $store.cart.addItem({ productId: {{ $product->id }}, title: @js($product->title), titleAr: @js($product->title_ar), basePrice: {{ $product->price }}, qty: 1, selectedOptions: [] });
                    @else
                    $store.modal.show(@js($product));
                    @endif
                "
                class="w-7 h-7 sm:w-9 sm:h-9 rounded-full bg-brand text-white flex items-center justify-center hover:bg-brand-dark active:scale-90 transition-all duration-150 shrink-0"
                aria-label="Add {{ $product->title }}"
            >
                <i data-lucide="plus" class="w-3.5 h-3.5 sm:w-4 sm:h-4"></i>
            </button>
        </div>
    </div>
</div>
