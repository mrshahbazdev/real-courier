<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login — {{ \App\Models\Setting::get('company_name') }}</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    <div class="login-wrap">
        <div class="login-card">
            <h1>Admin Login</h1>
            <p class="sub">{{ \App\Models\Setting::get('company_name') }} control panel</p>

            @if ($errors->any())
                <div class="notice-error" style="margin:0 0 16px">{{ $errors->first() }}</div>
            @endif

            <form method="POST" action="{{ route('admin.login.post') }}">
                @csrf
                <div class="field">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus>
                </div>
                <div class="field">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <button class="btn btn-primary" type="submit" style="width:100%">Sign In</button>
            </form>
        </div>
    </div>
</body>
</html>
