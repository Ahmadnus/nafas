import Alpine from 'alpinejs';
import { cartBadgePulse } from './animations';

const STORAGE_KEY = 'frutta_cart_v1';

function loadCart() {
    try {
        const raw = localStorage.getItem(STORAGE_KEY);
        return raw ? JSON.parse(raw) : [];
    } catch {
        return [];
    }
}

function saveCart(items) {
    localStorage.setItem(STORAGE_KEY, JSON.stringify(items));
}

function lineTotal(item) {
    const optionsTotal = item.selectedOptions.reduce((sum, o) => sum + Number(o.price_delta), 0);
    return (Number(item.basePrice) + optionsTotal) * item.qty;
}

export default function cartStore() {
    return {
        items: loadCart(),
        open: false,
        pulse: false,
        currency: window.APP_CURRENCY || 'Rs.',
        whatsappNumber: window.APP_WHATSAPP || '',
        note: '',
        customerName: '',
        customerAddress: '',
        checkout: false,

        persist() {
            saveCart(this.items);
        },

        get count() {
            return this.items.reduce((sum, i) => sum + i.qty, 0);
        },

        get subtotal() {
            return this.items.reduce((sum, i) => sum + lineTotal(i), 0);
        },

        lineTotal(item) {
            return lineTotal(item);
        },

        addItem(item) {
            // item: { productId, title, basePrice, qty, selectedOptions: [{group,label,price_delta}] }
            const signature = JSON.stringify({
                id: item.productId,
                opts: item.selectedOptions.map((o) => o.label).sort(),
            });

            const existing = this.items.find(
                (i) => JSON.stringify({ id: i.productId, opts: i.selectedOptions.map((o) => o.label).sort() }) === signature
            );

            if (existing) {
                existing.qty += item.qty;
            } else {
                this.items.push({ ...item, uid: crypto.randomUUID() });
            }

            this.items = [...this.items];
            this.persist();
            this.bump();
        },

        addOffer(offer, product) {
            if (product && window.trackProductClick) window.trackProductClick(product.id);
            const hasOptions = product && Array.isArray(product.option_groups) && product.option_groups.length > 0;

            if (hasOptions) {
                Alpine.store('modal').show(product, offer.discount_price ? Number(offer.discount_price) : null);
                return;
            }

            const price = offer.discount_price
                ? Number(offer.discount_price)
                : (product ? Number(product.price) : 0);

            this.addItem({
                productId: product ? product.id : null,
                title: offer.title,
                titleAr: offer.title_ar,
                basePrice: price,
                qty: 1,
                selectedOptions: [],
                isOffer: true,
            });
            this.open = true;
        },

        removeItem(uid) {
            this.items = this.items.filter((i) => i.uid !== uid);
            this.persist();
        },

        incQty(uid) {
            const item = this.items.find((i) => i.uid === uid);
            if (item) item.qty++;
            this.items = [...this.items];
            this.persist();
        },

        decQty(uid) {
            const item = this.items.find((i) => i.uid === uid);
            if (item) {
                item.qty--;
                if (item.qty <= 0) {
                    this.removeItem(uid);
                    return;
                }
            }
            this.items = [...this.items];
            this.persist();
        },

        clear() {
            this.items = [];
            this.persist();
        },

        bump() {
            this.pulse = true;
            const badge = document.querySelector('[data-cart-badge]');
            if (badge) cartBadgePulse(badge);
            setTimeout(() => (this.pulse = false), 400);
        },

        buildWhatsAppMessage() {
            const lang = Alpine.store('lang');
            const t = (key) => lang.t(key);
            const appName = lang.current === 'ar' ? window.APP_NAME_AR || window.APP_NAME : window.APP_NAME;

            const lines = [];
            lines.push(`*${t('new_order')} — ${appName || 'Nafas - Juice & Patisserie'}*`);
            lines.push('');

            const name = this.customerName.trim();
            const address = this.customerAddress.trim();
            if (name) lines.push(`${t('customer_name_label')}: ${name}`);
            if (address) lines.push(`${t('customer_address_label')}: ${address}`);
            if (name || address) lines.push('');

            this.items.forEach((item, idx) => {
                const title = lang.pick(item.titleAr, item.title);
                const prefix = item.isOffer ? '🔥 ' : '';
                lines.push(`${idx + 1}. ${prefix}${title} x${item.qty}`);
                if (item.selectedOptions.length) {
                    item.selectedOptions.forEach((o) => {
                        const groupLabel = lang.pick(o.groupAr, o.group);
                        const optLabel = lang.pick(o.labelAr, o.label);
                        const deltaText = Number(o.price_delta) > 0 ? ` (+${lang.currency}${o.price_delta})` : '';
                        lines.push(`    - ${groupLabel}: ${optLabel}${deltaText}`);
                    });
                }
                lines.push(`    ${t('subtotal')}: ${lang.currency}${lineTotal(item).toFixed(0)}`);
            });

            lines.push('');
            lines.push(`*${t('total')}: ${lang.currency}${this.subtotal.toFixed(0)}*`);

            if (this.note.trim()) {
                lines.push('');
                lines.push(`${t('note_label')}: ${this.note.trim()}`);
            }

            return lines.join('\n');
        },

        startCheckout() {
            if (!this.items.length) return;
            this.checkout = true;
        },

        cancelCheckout() {
            this.checkout = false;
        },

        confirmOrder() {
            this.checkout = false;
            this.sendViaWhatsApp();
        },

        sendViaWhatsApp() {
            if (!this.items.length) return;
            const message = this.buildWhatsAppMessage();
            const url = `https://wa.me/${this.whatsappNumber}?text=${encodeURIComponent(message)}`;
            window.open(url, '_blank');
        },
    };
}
