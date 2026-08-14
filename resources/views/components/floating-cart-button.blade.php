<button
    x-show="$store.cart.count > 0"
    x-cloak
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 scale-50"
    x-transition:enter-end="opacity-100 scale-100"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100 scale-100"
    x-transition:leave-end="opacity-0 scale-50"
    @click="$store.cart.open = true"
    class="fixed bottom-5 end-5 z-30 bg-brand hover:bg-brand-dark text-white rounded-full shadow-float w-12 h-12 sm:w-14 sm:h-14 flex items-center justify-center active:scale-95 transition-transform"
>
    <span class="relative">
        <i data-lucide="shopping-cart" class="w-5 h-5 sm:w-6 sm:h-6"></i>
        <span
            data-cart-badge
            x-show="$store.cart.count > 0"
            x-text="$store.cart.count"
            class="absolute -top-2 -end-2 bg-accent-2 text-white text-[10px] font-bold w-4 h-4 rounded-full flex items-center justify-center"
        ></span>
    </span>
</button>
