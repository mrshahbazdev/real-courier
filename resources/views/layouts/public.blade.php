<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', $settings['company_name'].' — '.$settings['tagline'])</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&family=Fira+Code:wght@500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    <div class="topbar">
        <div class="container">
            <div class="topbar-left">
                <span>{{ $settings['email'] }}</span>
                <span class="sep">|</span>
                <span>{{ $settings['phone'] }}</span>
            </div>
            <div class="topbar-right">
                <span>{{ $settings['address'] }}</span>
            </div>
        </div>
    </div>

    <header class="site-header">
        <div class="container">
            <a class="brand" href="{{ route('home') }}">
                @if (!empty($settings['logo']))
                    <img src="{{ asset($settings['logo']) }}" alt="{{ $settings['company_name'] }} logo">
                @endif
                <span>
                    <span class="brand-name">{{ $settings['company_name'] }}</span>
                    <span class="brand-tag">{{ $settings['tagline'] }}</span>
                </span>
            </a>
            <nav class="site-nav">
                <a href="{{ route('home') }}">Home</a>
                <a href="{{ route('home') }}#services">Services</a>
                <a href="{{ route('home') }}#about">About</a>
                <a href="{{ route('home') }}#track">Track</a>
                <a href="{{ route('home') }}#contact">Contact</a>
            </nav>
            <a class="btn btn-primary btn-sm" href="{{ route('home') }}#track">Track Shipment</a>
        </div>
    </header>

    @yield('content')

    <section class="contact-strip" id="contact">
        <div class="container">
            <div>
                <div class="k">Head Office</div>
                <div class="v">{{ $settings['address'] }}</div>
            </div>
            <div>
                <div class="k">Email</div>
                <div class="v">{{ $settings['email'] }}</div>
            </div>
            <div>
                <div class="k">Phone</div>
                <div class="v">{{ $settings['phone'] }}</div>
            </div>
        </div>
    </section>

    <footer class="site-footer">
        <div class="container">
            <span>&copy; {{ date('Y') }} {{ $settings['company_name'] }}. {{ $settings['footer_text'] }}</span>
            <a href="{{ route('admin.login') }}" style="color:#5b6b85">Admin Login</a>
        </div>
    </footer>
</body>
</html>
