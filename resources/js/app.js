import Alpine from 'alpinejs';
import intersect from '@alpinejs/intersect';
import gsap from 'gsap';
import { createIcons, icons } from 'lucide';
import cartStore from './cart';
import productModal from './product-modal';
import langStore from './lang';
import { heroEntrance, staggerIn, observeStagger, cartBadgePulse, flyToCart, floatingBackground } from './animations';

Alpine.plugin(intersect);
Alpine.store('cart', cartStore());
Alpine.store('modal', productModal());
Alpine.store('lang', langStore());

document.documentElement.setAttribute('dir', Alpine.store('lang').dir);
document.documentElement.setAttribute('lang', Alpine.store('lang').current);

window.Alpine = Alpine;
window.gsap = gsap;
window.gsapAnimations = { heroEntrance, staggerIn, observeStagger, cartBadgePulse, flyToCart, floatingBackground };

window.trackProductClick = function (productId) {
    const token = document.querySelector('meta[name="csrf-token"]')?.content;
    if (!token) return;
    fetch(`/products/${productId}/click`, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': token, Accept: 'application/json' },
        keepalive: true,
    }).catch(() => {});
};

function renderIcons() {
    createIcons({ icons });
}

document.addEventListener('alpine:init', () => {
    // stores/components registered above run before this fires
});

document.addEventListener('DOMContentLoaded', () => {
    renderIcons();

    if (document.querySelector('[data-hero-title]')) {
        heroEntrance();
    }

    if (document.querySelector('[data-offer-billboard]')) {
        staggerIn('[data-offer-billboard]', { duration: 0.6, stagger: 0, delay: 0.4, y: 30 });
    }

    if (document.querySelector('[data-floating-bg]')) {
        floatingBackground();
    }
});
document.addEventListener('icons:refresh', renderIcons);

Alpine.start();
