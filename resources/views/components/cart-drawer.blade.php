@props(['currency'])

<div x-show="$store.cart.open" x-cloak class="fixed inset-0 z-40">
    <div
        x-show="$store.cart.open"
        x-transition:enter="transition-opacity ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition-opacity ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="absolute inset-0 bg-black/50 backdrop-blur-sm"
        @click="$store.cart.open = false"
    ></div>

    <div
        x-show="$store.cart.open"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="translate-x-full"
        x-transition:enter-end="translate-x-0"
        x-transition:leave="transition ease-in duration-250"
        x-transition:leave-start="translate-x-0"
        x-transition:leave-end="translate-x-full"
        class="absolute right-0 top-0 h-full w-full sm:w-[420px] bg-surface-raised shadow-float flex flex-col"
        x-init="$watch('$store.cart.open', v => v && $nextTick(() => document.dispatchEvent(new Event('icons:refresh'))))"
    >
        <div class="flex items-center justify-between px-5 py-4 border-b border-border">
            <h3 class="font-bold text-lg text-text" x-text="$store.lang.t('your_order')"></h3>
            <button @click="$store.cart.open = false" class="w-9 h-9 rounded-full hover:bg-surface-alt flex items-center justify-center active:scale-90 transition-all">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>
        </div>

        <div class="flex-1 overflow-y-auto px-5 py-4 space-y-3">
            <template x-if="$store.cart.items.length === 0">
                <div class="h-full flex flex-col items-center justify-center text-center text-text-muted gap-3 py-16">
                    <i data-lucide="shopping-bag" class="w-10 h-10 opacity-40"></i>
                    <p x-text="$store.lang.t('empty_cart')"></p>
                </div>
            </template>

            <template x-for="item in $store.cart.items" :key="item.uid">
                <div class="border border-border rounded-2xl p-3 flex gap-3">
                    <div class="flex-1 min-w-0">
                        <div class="flex items-start justify-between gap-2">
                            <p class="font-semibold text-text text-sm" x-text="$store.lang.pick(item.titleAr, item.title)"></p>
                            <button @click="$store.cart.removeItem(item.uid)" class="text-text-muted hover:text-danger transition-colors">
                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                            </button>
                        </div>
                        <template x-if="item.selectedOptions.length">
                            <ul class="text-xs text-text-muted mt-1 space-y-0.5">
                                <template x-for="opt in item.selectedOptions" :key="opt.label">
                                    <li x-text="$store.lang.pick(opt.groupAr, opt.group) + ': ' + $store.lang.pick(opt.labelAr, opt.label) + (Number(opt.price_delta) > 0 ? ' (+' + $store.lang.currency + Number(opt.price_delta).toFixed(0) + ')' : '')"></li>
                                </template>
                            </ul>
                        </template>

                        <div class="flex items-center justify-between mt-2">
                            <div class="flex items-center gap-2 bg-surface-alt rounded-full px-1.5 py-1">
                                <button @click="$store.cart.decQty(item.uid)" class="w-6 h-6 rounded-full bg-surface-raised shadow-card flex items-center justify-center active:scale-90 transition-transform">
                                    <i data-lucide="minus" class="w-3 h-3"></i>
                                </button>
                                <span class="w-5 text-center text-sm font-semibold" x-text="item.qty"></span>
                                <button @click="$store.cart.incQty(item.uid)" class="w-6 h-6 rounded-full bg-surface-raised shadow-card flex items-center justify-center active:scale-90 transition-transform">
                                    <i data-lucide="plus" class="w-3 h-3"></i>
                                </button>
                            </div>
                            <span class="font-semibold text-sm text-text" x-text="$store.lang.currency + $store.cart.lineTotal(item).toFixed(0)"></span>
                        </div>
                    </div>
                </div>
            </template>
        </div>

        <div x-show="$store.cart.items.length > 0" class="border-t border-border p-5 space-y-4">
            <textarea
                x-model="$store.cart.note"
                rows="2"
                :placeholder="$store.lang.t('note_placeholder')"
                class="w-full text-sm rounded-xl border border-border bg-surface-alt px-3 py-2 focus:outline-none focus:ring-2 focus:ring-brand/40 resize-none"
            ></textarea>

            <div class="flex items-center justify-between font-bold text-text">
                <span x-text="$store.lang.t('total')"></span>
                <span x-text="$store.lang.currency + $store.cart.subtotal.toFixed(0)"></span>
            </div>

            <button
                @click="$store.cart.sendViaWhatsApp()"
                class="w-full bg-[#25D366] hover:brightness-95 text-white font-semibold py-3.5 rounded-full flex items-center justify-center gap-2 active:scale-95 transition-all duration-150 shadow-card"
            >
                <i data-lucide="message-circle" class="w-5 h-5"></i>
                <span x-text="$store.lang.t('send_whatsapp')"></span>
            </button>
        </div>
    </div>
</div>
