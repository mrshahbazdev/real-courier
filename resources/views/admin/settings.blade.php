@extends('layouts.admin')

@section('title', 'Site Settings')

@section('content')
<h1 style="margin-bottom:26px">Site Settings</h1>

@if ($errors->any())
    <div class="notice-error" style="margin-bottom:20px">
        <ul style="margin-left:18px">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
@endif

<form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data">
    @csrf

    <div class="panel">
        <h2>Company Identity</h2>
        <div class="form-grid">
            <div class="field"><label>Company Name *</label><input name="company_name" required value="{{ old('company_name', $settings['company_name']) }}"></div>
            <div class="field"><label>Tagline</label><input name="tagline" value="{{ old('tagline', $settings['tagline']) }}"></div>
            <div class="field"><label>Head Office (shown on tracking card)</label><input name="head_office" value="{{ old('head_office', $settings['head_office']) }}"></div>
            <div class="field"><label>Address (header/footer)</label><input name="address" value="{{ old('address', $settings['address']) }}"></div>
            <div class="field"><label>Email</label><input type="email" name="email" value="{{ old('email', $settings['email']) }}"></div>
            <div class="field"><label>Phone</label><input name="phone" value="{{ old('phone', $settings['phone']) }}"></div>
            <div class="field">
                <label>Logo</label>
                @if ($settings['logo'])<img class="preview-img" src="{{ asset($settings['logo']) }}">@endif
                <input type="file" name="logo" accept="image/*">
            </div>
            <div class="field">
                <label>Authorized Signature (shown on tracking card)</label>
                @if ($settings['signature'])<img class="preview-img" src="{{ asset($settings['signature']) }}">@endif
                <input type="file" name="signature" accept="image/*">
            </div>
            <div class="field full"><label>Signature Label</label><input name="authorized_signature_name" value="{{ old('authorized_signature_name', $settings['authorized_signature_name']) }}" placeholder="Authorized Signature"></div>
        </div>
    </div>

    <div class="panel">
        <h2>Homepage — Hero</h2>
        <div class="form-grid">
            <div class="field full"><label>Hero Title</label><input name="hero_title" value="{{ old('hero_title', $settings['hero_title']) }}"></div>
            <div class="field full"><label>Hero Text</label><textarea name="hero_text" rows="2">{{ old('hero_text', $settings['hero_text']) }}</textarea></div>
            <div class="field">
                <label>Hero Background Image</label>
                @if ($settings['hero_image'])<img class="preview-img" src="{{ asset($settings['hero_image']) }}">@endif
                <input type="file" name="hero_image" accept="image/*">
            </div>
        </div>
    </div>

    <div class="panel">
        <h2>Homepage — Services</h2>
        <div class="form-grid">
            @for ($i = 1; $i <= 3; $i++)
                <div class="field"><label>Service {{ $i }} Title</label><input name="service{{ $i }}_title" value="{{ old('service'.$i.'_title', $settings['service'.$i.'_title']) }}"></div>
                <div class="field"><label>Service {{ $i }} Text</label><input name="service{{ $i }}_text" value="{{ old('service'.$i.'_text', $settings['service'.$i.'_text']) }}"></div>
            @endfor
        </div>
    </div>

    <div class="panel">
        <h2>Homepage — About & Stats</h2>
        <div class="form-grid">
            <div class="field full"><label>About Title</label><input name="about_title" value="{{ old('about_title', $settings['about_title']) }}"></div>
            <div class="field full"><label>About Text</label><textarea name="about_text" rows="4">{{ old('about_text', $settings['about_text']) }}</textarea></div>
            <div class="field">
                <label>About Image</label>
                @if ($settings['about_image'])<img class="preview-img" src="{{ asset($settings['about_image']) }}">@endif
                <input type="file" name="about_image" accept="image/*">
            </div>
            @for ($i = 1; $i <= 3; $i++)
                <div class="field"><label>Stat {{ $i }} Number</label><input name="stat{{ $i }}_num" value="{{ old('stat'.$i.'_num', $settings['stat'.$i.'_num']) }}"></div>
                <div class="field"><label>Stat {{ $i }} Label</label><input name="stat{{ $i }}_label" value="{{ old('stat'.$i.'_label', $settings['stat'.$i.'_label']) }}"></div>
            @endfor
        </div>
    </div>

    <div class="panel">
        <h2>Dropdown Lists (statuses & charge names)</h2>
        <div class="form-grid">
            <div class="field">
                <label>Status Options (one per line)</label>
                <textarea name="status_options" rows="8">{{ old('status_options', $settings['status_options']) }}</textarea>
                <div class="help">Shipment form aur tracking events me dropdown me ye options aate hain.</div>
            </div>
            <div class="field">
                <label>Charge Names (one per line)</label>
                <textarea name="charge_options" rows="8">{{ old('charge_options', $settings['charge_options']) }}</textarea>
                <div class="help">Charges add karte waqt dropdown me ye names aate hain — amount har shipment par alag hota hai.</div>
            </div>
            <div class="field">
                <label>Default Invoice Template</label>
                <select name="default_invoice_template">
                    @foreach (\App\Models\Setting::INVOICE_TEMPLATES as $key => $name)
                        <option value="{{ $key }}" @selected(old('default_invoice_template', $settings['default_invoice_template']) === $key)>{{ $name }}</option>
                    @endforeach
                </select>
                <div class="help">5 templates — har shipment par alag template bhi choose kar sakte ho (edit page par).</div>
            </div>
        </div>
    </div>

    <div class="panel">
        <h2>Footer & Tracking Card</h2>
        <div class="field full"><label>Footer Text</label><input name="footer_text" value="{{ old('footer_text', $settings['footer_text']) }}" style="width:100%;padding:11px 13px;border:1.5px solid var(--line);border-radius:8px;font:inherit"></div>
        <div style="margin-top:18px">
            <label style="display:block;font-size:13px;font-weight:700;color:var(--navy);margin-bottom:8px">Payment Methods (shown on tracking card)</label>
            @php $enabled = \App\Models\Setting::enabledPaymentMethods(); @endphp
            <div class="checks">
                @foreach (\App\Models\Setting::PAYMENT_METHODS as $m)
                    <label><input type="checkbox" name="payment_methods[]" value="{{ $m }}" {{ in_array($m, old('payment_methods', $enabled)) ? 'checked' : '' }}> {{ ucfirst($m) }}</label>
                @endforeach
            </div>
        </div>
    </div>

    <button class="btn btn-accent" type="submit">Save Settings</button>
</form>

<div class="panel" style="margin-top:24px">
    <h2>Admin Account</h2>
    <form method="POST" action="{{ route('admin.settings.account') }}" class="form-grid">
        @csrf
        <div class="field"><label>Name</label><input name="name" value="{{ auth()->user()->name }}" required></div>
        <div class="field"><label>Email</label><input type="email" name="email" value="{{ auth()->user()->email }}" required></div>
        <div class="field"><label>New Password (leave blank to keep)</label><input type="password" name="password" minlength="8"></div>
        <div class="field"><label>Confirm Password</label><input type="password" name="password_confirmation"></div>
        <div class="full"><button class="btn btn-primary btn-sm" type="submit">Update Account</button></div>
    </form>
</div>
@endsection
