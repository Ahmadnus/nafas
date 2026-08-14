import gsap from 'gsap';

export function heroEntrance() {
    const logo = document.querySelector('[data-hero-logo]');

    gsap.from(logo, {
        y: -16,
        scale: 0.7,
        opacity: 0,
        duration: 0.8,
        ease: 'back.out(1.7)',
        onComplete: () => logo && pulseLogo(logo),
    });
    gsap.from('[data-hero-eyebrow]', { y: 20, opacity: 0, duration: 0.6, delay: 0.15, ease: 'power3.out' });
    gsap.from('[data-hero-title]', { y: 30, opacity: 0, duration: 0.7, delay: 0.1, ease: 'power3.out' });
    gsap.from('[data-hero-sub]', { y: 20, opacity: 0, duration: 0.6, delay: 0.25, ease: 'power3.out' });
    gsap.from('[data-hero-cta]', { y: 20, opacity: 0, duration: 0.6, delay: 0.35, ease: 'power3.out' });
}

export function pulseLogo(el) {
    gsap.to(el, {
        scale: 1.08,
        duration: 1.6,
        repeat: -1,
        yoyo: true,
        ease: 'sine.inOut',
    });
    gsap.to(el, {
        y: -8,
        rotation: 2,
        duration: 2.4,
        repeat: -1,
        yoyo: true,
        ease: 'sine.inOut',
        delay: 0.2,
    });
    gsap.to(el, {
        filter: 'drop-shadow(0 0 22px rgba(255,255,255,0.55))',
        duration: 1.6,
        repeat: -1,
        yoyo: true,
        ease: 'sine.inOut',
    });
}

export function staggerIn(selector, opts = {}) {
    const els = document.querySelectorAll(selector);
    if (!els.length) return;

    gsap.from(els, {
        y: 24,
        opacity: 0,
        duration: 0.5,
        stagger: 0.06,
        ease: 'power2.out',
        ...opts,
    });
}

export function observeStagger(containerSelector, itemSelector, opts = {}) {
    const containers = document.querySelectorAll(containerSelector);

    containers.forEach((container) => {
        const io = new IntersectionObserver(
            (entries) => {
                entries.forEach((entry) => {
                    if (entry.isIntersecting) {
                        staggerIn(itemSelector === containerSelector ? itemSelector : `${containerSelector} ${itemSelector}`, opts);
                        io.unobserve(entry.target);
                    }
                });
            },
            { threshold: 0.1 }
        );
        io.observe(container);
    });
}

export function popIn(el) {
    gsap.fromTo(el, { scale: 0.85, opacity: 0 }, { scale: 1, opacity: 1, duration: 0.35, ease: 'back.out(2)' });
}

export function cartBadgePulse(el) {
    gsap.fromTo(el, { scale: 1 }, { scale: 1.35, duration: 0.15, yoyo: true, repeat: 1, ease: 'power1.inOut' });
}

const FLOAT_ICONS = [
    '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M5.5 8h13l-1.4 11.2a2 2 0 0 1-2 1.8H8.9a2 2 0 0 1-2-1.8L5.5 8Z"/><path d="M9 8a3 3 0 0 1 6 0"/><path d="M12 2v3"/></svg>', // juice cup
    '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="12" cy="13" r="8"/><path d="M12 5C10.5 3 9 2.5 7 3c.5 1.5 1.5 2.5 3 3"/></svg>', // fruit / apple
    '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M12 2a10 10 0 0 1 10 10c0 .34-.02.67-.05 1H2.05C2.02 12.67 2 12.34 2 12A10 10 0 0 1 12 2Z"/><path d="M2.05 13c.5 5 4.7 9 9.95 9s9.45-4 9.95-9"/><path d="M12 2v20"/></svg>', // orange slice
    '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M18 8h1a4 4 0 0 1 0 8h-1"/><path d="M2 8h16v9a4 4 0 0 1-4 4H6a4 4 0 0 1-4-4V8Z"/><line x1="6" y1="1" x2="6" y2="4"/><line x1="10" y1="1" x2="10" y2="4"/><line x1="14" y1="1" x2="14" y2="4"/></svg>', // cup/dessert
];

export function floatingBackground(containerSelector = '[data-floating-bg]') {
    const container = document.querySelector(containerSelector);
    if (!container || container.dataset.floatingReady) return;
    container.dataset.floatingReady = '1';

    const count = window.innerWidth < 640 ? 5 : 9;

    for (let i = 0; i < count; i++) {
        const el = document.createElement('div');
        el.className = 'floating-deco';
        el.innerHTML = FLOAT_ICONS[i % FLOAT_ICONS.length];
        const size = gsap.utils.random(28, 64);
        el.style.cssText = `position:absolute;width:${size}px;height:${size}px;color:var(--color-brand);opacity:${gsap.utils.random(0.06, 0.16)};left:${gsap.utils.random(0, 92)}%;top:${gsap.utils.random(0, 100)}%;pointer-events:none;`;
        container.appendChild(el);

        gsap.to(el, {
            y: gsap.utils.random(-40, 40),
            x: gsap.utils.random(-30, 30),
            rotation: gsap.utils.random(-25, 25),
            duration: gsap.utils.random(6, 12),
            repeat: -1,
            yoyo: true,
            ease: 'sine.inOut',
            delay: gsap.utils.random(0, 3),
        });
    }
}

export function flyToCart(fromEl, toEl) {
    if (!fromEl || !toEl) return;
    const fromRect = fromEl.getBoundingClientRect();
    const toRect = toEl.getBoundingClientRect();

    const clone = fromEl.cloneNode(true);
    clone.style.position = 'fixed';
    clone.style.left = fromRect.left + 'px';
    clone.style.top = fromRect.top + 'px';
    clone.style.width = fromRect.width + 'px';
    clone.style.height = fromRect.height + 'px';
    clone.style.zIndex = 9999;
    clone.style.pointerEvents = 'none';
    clone.style.borderRadius = '9999px';
    document.body.appendChild(clone);

    gsap.to(clone, {
        left: toRect.left + toRect.width / 2 - 10,
        top: toRect.top + toRect.height / 2 - 10,
        width: 20,
        height: 20,
        opacity: 0.4,
        duration: 0.6,
        ease: 'power2.in',
        onComplete: () => clone.remove(),
    });
}
