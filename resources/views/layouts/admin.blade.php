<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin') — {{ \App\Models\Setting::get('company_name') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&family=Fira+Code:wght@500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    <div class="admin-shell">
        <aside class="admin-side">
            <div class="brand-name">{{ \App\Models\Setting::get('company_name') }}</div>
            <a class="side-link {{ request()->routeIs('admin.dashboard') || request()->routeIs('admin.shipments.*') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                <svg fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                Shipments
            </a>
            <a class="side-link {{ request()->routeIs('admin.settings') ? 'active' : '' }}" href="{{ route('admin.settings') }}">
                <svg fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.3 4.3a1.7 1.7 0 013.4 0 1.7 1.7 0 002.6 1 1.7 1.7 0 012.4 2.4 1.7 1.7 0 001 2.6 1.7 1.7 0 010 3.4 1.7 1.7 0 00-1 2.6 1.7 1.7 0 01-2.4 2.4 1.7 1.7 0 00-2.6 1 1.7 1.7 0 01-3.4 0 1.7 1.7 0 00-2.6-1 1.7 1.7 0 01-2.4-2.4 1.7 1.7 0 00-1-2.6 1.7 1.7 0 010-3.4 1.7 1.7 0 001-2.6 1.7 1.7 0 012.4-2.4 1.7 1.7 0 002.6-1z"/><circle cx="12" cy="12" r="3"/></svg>
                Site Settings
            </a>
            <a class="side-link" href="{{ route('home') }}" target="_blank">
                <svg fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                View Site
            </a>
            <div class="side-bottom">
                <form method="POST" action="{{ route('admin.logout') }}">
                    @csrf
                    <button class="side-link" style="background:none;border:0;width:100%;text-align:left;cursor:pointer;font:inherit;color:#c3cfe6;padding:10px 12px;border-radius:8px" type="submit">
                        Logout
                    </button>
                </form>
            </div>
        </aside>
        <main class="admin-main">
            @if (session('success'))
                <div class="flash">{{ session('success') }}</div>
            @endif
            @yield('content')
        </main>
    </div>
</body>
</html>
