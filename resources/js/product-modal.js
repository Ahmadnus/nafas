import Alpine from 'alpinejs';

export default function productModal() {
    return {
        open: false,
        product: null,
        qty: 1,
        selections: {}, // groupId -> optionId | [optionId]
        overridePrice: null,
        isOffer: false,

        show(product, overridePrice = null) {
            this.product = product;
            this.qty = 1;
            this.selections = {};
            this.overridePrice = overridePrice;
            this.isOffer = overridePrice !== null;

            product.option_groups.forEach((group) => {
                const defaultOption = group.options.find((o) => o.is_default);
                if (group.type === 'single') {
                    this.selections[group.id] = defaultOption ? defaultOption.id : (group.options[0]?.id ?? null);
                } else {
                    this.selections[group.id] = defaultOption ? [defaultOption.id] : [];
                }
            });

            this.open = true;
            document.body.style.overflow = 'hidden';
        },

        close() {
            this.open = false;
            document.body.style.overflow = '';
        },

        isChecked(groupId, optionId) {
            const sel = this.selections[groupId];
            return Array.isArray(sel) ? sel.includes(optionId) : sel === optionId;
        },

        toggleMultiple(groupId, optionId) {
            const sel = this.selections[groupId] || [];
            if (sel.includes(optionId)) {
                this.selections[groupId] = sel.filter((id) => id !== optionId);
            } else {
                this.selections[groupId] = [...sel, optionId];
            }
        },

        get optionsTotal() {
            if (!this.product) return 0;
            let total = 0;
            this.product.option_groups.forEach((group) => {
                const sel = this.selections[group.id];
                group.options.forEach((opt) => {
                    const picked = Array.isArray(sel) ? sel.includes(opt.id) : sel === opt.id;
                    if (picked) total += Number(opt.price_delta);
                });
            });
            return total;
        },

        get unitPrice() {
            if (!this.product) return 0;
            const base = this.overridePrice !== null ? Number(this.overridePrice) : Number(this.product.price);
            return base + this.optionsTotal;
        },

        get totalPrice() {
            return this.unitPrice * this.qty;
        },

        selectedOptionObjects() {
            const out = [];
            this.product.option_groups.forEach((group) => {
                const sel = this.selections[group.id];
                group.options.forEach((opt) => {
                    const picked = Array.isArray(sel) ? sel.includes(opt.id) : sel === opt.id;
                    if (picked) out.push({
                        group: group.name,
                        groupAr: group.name_ar,
                        label: opt.label,
                        labelAr: opt.label_ar,
                        price_delta: opt.price_delta,
                    });
                });
            });
            return out;
        },

        addToCart() {
            Alpine.store('cart').addItem({
                productId: this.product.id,
                title: this.product.title,
                titleAr: this.product.title_ar,
                basePrice: this.overridePrice !== null ? Number(this.overridePrice) : this.product.price,
                qty: this.qty,
                selectedOptions: this.selectedOptionObjects(),
                isOffer: this.isOffer,
            });
            this.close();
            Alpine.store('cart').open = true;
        },
    };
}
