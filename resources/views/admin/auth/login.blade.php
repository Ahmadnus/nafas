<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل دخول الإدارة</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-surface-alt text-text min-h-screen flex items-center justify-center px-4">
    <div class="w-full max-w-sm bg-surface-raised border border-border rounded-3xl shadow-card p-8">
        <div class="text-center mb-6">
            <div class="w-12 h-12 rounded-2xl bg-brand/10 text-brand flex items-center justify-center mx-auto mb-3">
                <i data-lucide="lock" class="w-5 h-5"></i>
            </div>
            <h1 class="text-xl font-bold">تسجيل دخول الإدارة</h1>
            <p class="text-sm text-text-muted">إدارة القائمة الرقمية</p>
        </div>

        @if($errors->any())
        <div class="mb-4 bg-danger/10 text-danger border border-danger/20 rounded-xl px-4 py-3 text-sm">
            بيانات الدخول غير صحيحة.
        </div>
        @endif

        <form method="POST" action="{{ route('admin.login') }}" class="space-y-4">
            @csrf
            <div>
                <label class="text-sm font-medium text-text-muted mb-1 block">البريد الإلكتروني</label>
                <input type="email" name="email" value="{{ old('email') }}" required autofocus
                    class="w-full rounded-xl border border-border px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand/40">
            </div>
            <div>
                <label class="text-sm font-medium text-text-muted mb-1 block">كلمة المرور</label>
                <input type="password" name="password" required
                    class="w-full rounded-xl border border-border px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand/40">
            </div>
            <button type="submit" class="w-full bg-brand hover:bg-brand-dark text-white font-semibold py-3 rounded-xl active:scale-95 transition-all">
                تسجيل الدخول
            </button>
        </form>
    </div>
</body>
</html>
