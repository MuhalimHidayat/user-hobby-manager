<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'CRUD User & Hobi')</title>
    @vite('resources/css/app.css')
</head>
<body class="min-h-screen bg-slate-950 text-slate-900 antialiased">
<div class="min-h-screen bg-[radial-gradient(circle_at_top_right,_rgba(79,70,229,0.2),_transparent_34%),linear-gradient(135deg,_#0f172a_0%,_#111827_45%,_#1e293b_100%)]">
    <nav class="border-b border-white/10 bg-slate-950/40 backdrop-blur-xl">
        <div class="mx-auto flex max-w-7xl items-center justify-between px-5 py-5 sm:px-8">
            <a href="{{ route('users.index') }}" class="group flex items-center gap-3">
                <span class="flex h-10 w-10 items-center justify-center rounded-2xl bg-indigo-500 text-lg font-bold text-white shadow-lg shadow-indigo-500/25 transition group-hover:bg-indigo-400">M</span>
                <span>
                    <span class="block text-sm font-semibold tracking-wide text-white">M Alim Hidayat</span>
                    <span class="block text-xs text-slate-400">People and passions</span>
                </span>
            </a>
            <span class="hidden rounded-full border border-white/10 bg-white/5 px-3 py-1.5 text-xs font-medium text-slate-300 sm:block">Admin workspace</span>
        </div>
    </nav>

    <main class="mx-auto max-w-7xl px-5 py-8 sm:px-8 sm:py-12">
        @if (session('success'))
            <div class="mb-6 flex items-center gap-3 rounded-2xl border border-emerald-400/20 bg-emerald-400/10 px-4 py-3 text-sm font-medium text-emerald-200" role="status">
                <span class="flex h-6 w-6 items-center justify-center rounded-full bg-emerald-400/20 text-emerald-300">+</span>
                {{ session('success') }}
            </div>
        @endif

        @yield('content')
    </main>
</div>

@stack('scripts')
</body>
</html>