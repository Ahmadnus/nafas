@props(['currency'])

<div
    x-show="$store.modal.open"
    x-cloak
    class="fixed inset-0 z-40 flex items-end sm:items-center justify-center"
>
    {{-- backdrop --}}
    <div
        x-show="$store.modal.open"
        x-transition:enter="transition-opacity ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition-opacity ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="absolute inset-0 bg-black/50 backdrop-blur-sm"
        @click="$store.modal.close()"
    ></div>

    {{-- panel --}}
    <div
        x-show="$store.modal.open"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-8 sm:scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
        x-transition:leave-end="opacity-0 translate-y-8 sm:scale-95"
        class="relative bg-surface-raised w-full sm:max-w-lg sm:rounded-3xl rounded-t-3xl max-h-[90vh] overflow-y-auto shadow-float"
        @click.outside="$store.modal.close()"
        x-init="$watch('$store.modal.open', v => v && $nextTick(() => document.dispatchEvent(new Event('icons:refresh'))))"
    >
        <template x-if="$store.modal.product">
            <div>
                <div class="relative aspect-[16/10]">
                    <img x-ref="modalImg" :src="$store.modal.product.image_url ?? '{{ asset('images/placeholder-product.svg') }}'" class="w-full h-full object-cover sm:rounded-t-3xl" :alt="$store.modal.product.title">
                    <button
                        @click="$store.modal.close()"
                        class="absolute top-4 end-4 w-9 h-9 rounded-full bg-black/40 text-white flex items-center justify-center backdrop-blur hover:bg-black/60 active:scale-90 transition-all"
                    >
                        <i data-lucide="x" class="w-4 h-4"></i>
                    </button>
                </div>

                <div class="p-5 sm:p-6">
                    <h3 class="font-heading text-xl font-bold text-text mb-1" x-text="$store.lang.pick($store.modal.product.title_ar, $store.modal.product.title)"></h3>
                    <p class="text-sm text-text-muted mb-4" x-text="$store.lang.pick($store.modal.product.description_ar, $store.modal.product.description)"></p>

                    <template x-for="group in $store.modal.product.option_groups" :key="group.id">
                        <div class="mb-5">
                            <div class="flex items-center justify-between mb-2">
                                <h4 class="font-semibold text-text text-sm" x-text="$store.lang.pick(group.name_ar, group.name)"></h4>
                                <span x-show="group.is_required" class="text-xs text-danger font-medium" x-text="$store.lang.t('required')"></span>
                            </div>
                            <div class="grid grid-cols-2 gap-2">
                                <template x-for="option in group.options" :key="option.id">
                                    <button
                                        type="button"
                                        @click="group.type === 'single' ? $store.modal.selections[group.id] = option.id : $store.modal.toggleMultiple(group.id, option.id)"
                                        :class="$store.modal.isChecked(group.id, option.id)
                                            ? 'border-brand bg-brand/10 text-brand'
                                            : 'border-border text-text hover:border-brand/40'"
                                        class="flex items-center justify-between gap-2 text-sm px-3 py-2 rounded-xl border transition-all duration-150 active:scale-95"
                                    >
                                        <span x-text="$store.lang.pick(option.label_ar, option.label)"></span>
                                        <span x-show="Number(option.price_delta) > 0" class="text-xs opacity-75" x-text="'+' + $store.lang.currency + Number(option.price_delta).toFixed(0)"></span>
                                    </button>
                                </template>
                            </div>
                        </div>
                    </template>

                    <div class="flex items-center justify-between border-t border-border pt-4 mt-2">
                        <div class="flex items-center gap-3 bg-surface-alt rounded-full px-2 py-1.5">
                            <button @click="$store.modal.qty > 1 && $store.modal.qty--" class="w-8 h-8 rounded-full bg-surface-raised shadow-card flex items-center justify-center active:scale-90 transition-transform">
                                <i data-lucide="minus" class="w-3.5 h-3.5"></i>
                            </button>
                            <span class="w-6 text-center font-semibold" x-text="$store.modal.qty"></span>
                            <button @click="$store.modal.qty++" class="w-8 h-8 rounded-full bg-surface-raised shadow-card flex items-center justify-center active:scale-90 transition-transform">
                                <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                            </button>
                        </div>

                        <button
                            @click="window.gsapAnimations.flyToCart($refs.modalImg, document.querySelector('[data-cart-badge]')); $store.modal.addToCart()"
                            class="flex-1 ms-4 bg-brand hover:bg-brand-dark text-white font-semibold py-3 rounded-full flex items-center justify-center gap-2 active:scale-95 transition-all duration-150 shadow-card"
                        >
                            <span x-text="$store.lang.t('add_to_cart')"></span>
                            <span x-text="$store.lang.currency + $store.modal.totalPrice.toFixed(0)"></span>
                        </button>
                    </div>
                </div>
            </div>
        </template>
    </div>
</div>
