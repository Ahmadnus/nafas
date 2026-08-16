@props(['title' => 'لوحة التحكم'])
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }} — إدارة نفس</title>
    <link rel="icon" href="{{ asset('images/brand/nafas-logo.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-surface-alt text-text" x-data="{ nav: false }" :class="nav && 'overflow-hidden'">
    <div class="flex min-h-screen">
        {{-- overlay for the mobile drawer --}}
        <div
            x-show="nav"
            x-transition.opacity
            @click="nav = false"
            class="fixed inset-0 z-30 bg-black/50 lg:hidden"
            x-cloak
        ></div>

        <aside
            class="fixed inset-y-0 start-0 z-40 w-72 max-w-[85vw] transition-transform duration-300 lg:static lg:z-auto lg:w-64 lg:max-w-none lg:translate-x-0 shrink-0 bg-surface-raised border-s border-border flex flex-col overflow-y-auto"
            :class="nav ? 'translate-x-0' : 'translate-x-full'"
            @keydown.escape.window="nav = false"
        >
            <div class="px-5 sm:px-6 py-5 border-b border-border flex items-center gap-3">
                <img src="{{ asset('images/brand/nafas-logo.png') }}" alt="نفس" class="w-10 h-10 object-contain">
                <div class="flex-1 min-w-0">
                    <p class="font-bold text-lg">نفس</p>
                    <p class="text-xs text-text-muted">إدارة القائمة</p>
                </div>
                <button type="button" @click="nav = false" class="lg:hidden -m-2 p-2 text-text-muted" aria-label="إغلاق القائمة">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>
            <nav class="flex-1 px-3 py-4 space-y-1">
                @php
                    $links = [
                        ['route' => 'admin.dashboard', 'label' => 'لوحة التحكم', 'icon' => 'layout-dashboard'],
                        ['route' => 'admin.categories.index', 'label' => 'الأقسام', 'icon' => 'list-tree'],
                        ['route' => 'admin.products.index', 'label' => 'المنتجات', 'icon' => 'shopping-basket'],
                        ['route' => 'admin.offers.index', 'label' => 'العروض واللافتات', 'icon' => 'megaphone'],
                        ['route' => 'admin.settings.edit', 'label' => 'الإعدادات', 'icon' => 'settings'],
                    ];
                @endphp
                @foreach($links as $link)
                <a
                    href="{{ route($link['route']) }}"
                    @click="nav = false"
                    @class([
                        'flex items-center gap-3 px-3 py-3 rounded-xl text-sm font-medium transition-colors',
                        'bg-brand text-white' => request()->routeIs(str_replace('.index', '', $link['route']) . '*'),
                        'text-text-muted hover:bg-surface-alt' => !request()->routeIs(str_replace('.index', '', $link['route']) . '*'),
                    ])
                >
                    <i data-lucide="{{ $link['icon'] }}" class="w-4 h-4"></i>
                    {{ $link['label'] }}
                </a>
                @endforeach
            </nav>
            <div class="px-3 py-4 border-t border-border">
                <a href="{{ route('menu') }}" target="_blank" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-text-muted hover:bg-surface-alt">
                    <i data-lucide="external-link" class="w-4 h-4"></i>
                    عرض القائمة المباشرة
                </a>
                <form method="POST" action="{{ route('admin.logout') }}">
                    @csrf
                    <button class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-danger hover:bg-danger/10">
                        <i data-lucide="log-out" class="w-4 h-4"></i>
                        تسجيل الخروج
                    </button>
                </form>
            </div>
        </aside>

        <div class="flex-1 min-w-0">
            <header class="lg:hidden sticky top-0 z-20 bg-surface-raised border-b border-border px-4 py-3 flex items-center gap-3">
                <button type="button" @click="nav = true" class="-m-2 p-2 text-text" aria-label="فتح القائمة">
                    <i data-lucide="menu" class="w-6 h-6"></i>
                </button>
                <div class="flex items-center gap-2 flex-1 min-w-0">
                    <img src="{{ asset('images/brand/nafas-logo.png') }}" alt="نفس" class="w-7 h-7 object-contain shrink-0">
                    <p class="font-bold truncate">{{ $title }}</p>
                </div>
                <a href="{{ route('menu') }}" target="_blank" class="text-sm text-brand shrink-0">عرض القائمة</a>
            </header>

            <main class="p-4 sm:p-6 max-w-6xl mx-auto">
                @if(session('status'))
                <div class="mb-6 bg-brand/10 text-brand border border-brand/20 rounded-xl px-4 py-3 text-sm font-medium">
                    {{ session('status') }}
                </div>
                @endif

                @if(session('error'))
                <div class="mb-6 bg-danger/10 text-danger border border-danger/20 rounded-xl px-4 py-3 text-sm font-medium">
                    {{ session('error') }}
                </div>
                @endif

                {{ $slot }}
            </main>
        </div>
    </div>
</body>
</html>
