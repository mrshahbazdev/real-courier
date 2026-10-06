@extends('layouts.public')

@section('content')
    <section class="hero" @if(!empty($settings['hero_image'])) style="background-image: linear-gradient(rgba(10,20,45,.78), rgba(10,20,45,.78)), url('{{ asset($settings['hero_image']) }}'); background-size: cover; background-position: center;" @endif>
        <div class="container">
            <div>
                <h1>{{ $settings['hero_title'] }}</h1>
                <p class="lead">{{ $settings['hero_text'] }}</p>
                <div class="trust-row">
                    <span><svg fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9 9 0 100-18 9 9 0 000 18z"/><path stroke-linecap="round" stroke-linejoin="round" d="M3.6 9h16.8M3.6 15h16.8M12 3a15 15 0 010 18"/></svg> Global network</span>
                    <span><svg fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg> Insured shipments</span>
                    <span><svg fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6l4 2"/><circle cx="12" cy="12" r="9"/></svg> 24/7 tracking</span>
                </div>
            </div>
            <div class="hero-card" id="track">
                <h2>Track Your Shipment</h2>
                <p class="hint">Enter your tracking number to see live status and delivery details.</p>
                <form class="track-form" method="GET" action="{{ route('track') }}">
                    <input type="text" name="tracking_no" placeholder="e.g. SHP 482 1239 8476" required value="{{ request('tracking_no') }}">
                    <button class="btn btn-accent" type="submit">Track</button>
                </form>
            </div>
        </div>
    </section>

    <section class="section" id="services">
        <div class="container">
            <h2 class="section-title">Our Services</h2>
            <p class="section-sub">End-to-end logistics for parcels, pallets and freight — delivered on time, every time.</p>
            <div class="cards-grid">
                <div class="service-card">
                    <div class="icon"><svg fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6 0a2 2 0 104 0 2 2 0 00-4 0zm-6 0a2 2 0 104 0 2 2 0 00-4 0z"/></svg></div>
                    <h3>{{ $settings['service1_title'] }}</h3>
                    <p>{{ $settings['service1_text'] }}</p>
                </div>
                <div class="service-card">
                    <div class="icon"><svg fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2 16l20-8-6 8-2-1-4 2 2-4-3-1z" transform="rotate(8 12 12)"/></svg></div>
                    <h3>{{ $settings['service2_title'] }}</h3>
                    <p>{{ $settings['service2_text'] }}</p>
                </div>
                <div class="service-card">
                    <div class="icon"><svg fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg></div>
                    <h3>{{ $settings['service3_title'] }}</h3>
                    <p>{{ $settings['service3_text'] }}</p>
                </div>
            </div>
        </div>
    </section>

    <section class="section about-sec" id="about">
        <div class="container about-grid">
            <div>
                @if (!empty($settings['about_image']))
                    <img class="about-img" src="{{ asset($settings['about_image']) }}" alt="About {{ $settings['company_name'] }}">
                @endif
            </div>
            <div>
                <h2 class="section-title">{{ $settings['about_title'] }}</h2>
                <p class="section-sub">{{ $settings['about_text'] }}</p>
                <div class="stats-row">
                    <div class="stat"><div class="num">{{ $settings['stat1_num'] }}</div><div class="lbl">{{ $settings['stat1_label'] }}</div></div>
                    <div class="stat"><div class="num">{{ $settings['stat2_num'] }}</div><div class="lbl">{{ $settings['stat2_label'] }}</div></div>
                    <div class="stat"><div class="num">{{ $settings['stat3_num'] }}</div><div class="lbl">{{ $settings['stat3_label'] }}</div></div>
                </div>
            </div>
        </div>
    </section>

    <section class="section steps-sec">
        <div class="container">
            <h2 class="section-title">How It Works</h2>
            <p class="section-sub">Three simple steps from booking to delivery.</p>
            <div class="cards-grid">
                <div class="service-card"><h3>1. Book & Register</h3><p>Your shipment is registered and a unique tracking number is issued.</p></div>
                <div class="service-card"><h3>2. In Transit</h3><p>Follow every checkpoint with real-time status updates.</p></div>
                <div class="service-card"><h3>3. Delivered</h3><p>Safe handover to the consignee with delivery confirmation.</p></div>
            </div>
        </div>
    </section>
@endsection
