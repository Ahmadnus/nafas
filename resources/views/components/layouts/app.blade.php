<!DOCTYPE html>
<html lang="ar" dir="rtl" class="no-js">
<head>
    <meta charset="UTF-8">
    <script>document.documentElement.classList.remove('no-js');</script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $settings['restaurant_name_ar'] ?? $settings['restaurant_name'] ?? 'نفس' }} — قائمة رقمية</title>
    <link rel="icon" href="{{ asset('images/brand/nafas-logo.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@400;500;600;700&family=Lalezar&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased" x-data>
    <script>
        window.APP_NAME = @json($settings['restaurant_name'] ?? '');
        window.APP_NAME_AR = @json($settings['restaurant_name_ar'] ?? '');
        window.APP_WHATSAPP = @json($settings['whatsapp_number'] ?? '');
        window.APP_CURRENCY = @json($settings['currency_symbol'] ?? 'Rs.');
        window.APP_LOGO = @json(asset('images/brand/nafas-logo.png'));
    </script>

    {{-- PRE-LOADER: liquid-fill logo reveal --}}
    <div
        data-preloader
        x-data
        x-init="
            const reveal = () => {
                const tl = gsap.timeline({ onComplete: () => $el.remove() });
                tl.to('[data-preloader-fill]', { height: '100%', duration: 1.1, ease: 'power2.inOut' })
                  .to('[data-preloader-logo]', { scale: 1.06, duration: 0.25, ease: 'power1.out' }, '-=0.3')
                  .to($el, { opacity: 0, duration: 0.5, ease: 'power1.out', delay: 0.15 })
            };
            document.readyState === 'complete' ? reveal() : window.addEventListener('load', reveal);
        "
        class="fixed inset-0 z-[9999] bg-surface flex items-center justify-center overflow-hidden"
    >
        <div class="relative w-48 h-48 sm:w-64 sm:h-64">
            <img src="{{ asset('images/brand/nafas-logo.png') }}" alt="نفس" data-preloader-logo class="relative z-10 w-full h-full object-contain drop-shadow-2xl">
            <div data-preloader-fill class="absolute inset-x-0 bottom-0 h-0 bg-brand/25 z-0" style="border-radius: 0 0 999px 999px;"></div>
        </div>
    </div>

    {{-- Ambient floating juice-cup / fruit-slice decorations --}}
    <div data-floating-bg class="fixed inset-0 pointer-events-none overflow-hidden z-0"></div>

    <div class="relative z-[1]">
        {{ $slot }}
    </div>
</body>
</html>
