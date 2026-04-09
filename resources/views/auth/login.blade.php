<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login — AndroidPlayer4K</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        * { box-sizing: border-box; }
        body { font-family: 'Inter', system-ui, -apple-system, sans-serif; margin:0; }
        @keyframes fadeUp { from { opacity:0; transform:translateY(20px); } to { opacity:1; transform:translateY(0); } }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen" style="background:#0f1923;">

{{-- Decorative gradient --}}
<div style="position:fixed; top:-30%; left:-10%; width:60%; height:60%; background:radial-gradient(circle, rgba(105,108,255,0.08) 0%, transparent 70%); pointer-events:none;"></div>
<div style="position:fixed; bottom:-30%; right:-10%; width:60%; height:60%; background:radial-gradient(circle, rgba(105,108,255,0.05) 0%, transparent 70%); pointer-events:none;"></div>

<div class="w-full max-w-md px-4" style="animation:fadeUp 0.5s ease-out;">
    <div style="background:#1a2d3f; border:1px solid rgba(255,255,255,0.06); border-radius:20px; padding:40px 36px; box-shadow:0 25px 60px rgba(0,0,0,0.3);">

        {{-- Logo --}}
        <div class="text-center" style="margin-bottom:36px;">
            <div style="width:56px; height:56px; background:linear-gradient(135deg, #696cff, #5f61e6); border-radius:16px; display:flex; align-items:center; justify-content:center; margin:0 auto 16px;">
                <svg width="28" height="28" fill="none" viewBox="0 0 24 24" stroke="white" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/><path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <h1 style="font-size:1.5rem; font-weight:700; color:#e2e8f0; margin:0;">Android<span style="color:#696cff;">Player4K</span></h1>
            <p style="font-size:0.8125rem; color:#5a6f80; margin-top:6px;">Admin Panel — Sign in to continue</p>
        </div>

        {{-- Validation errors --}}
        @if ($errors->any())
            <div style="display:flex; align-items:center; gap:10px; padding:12px 16px; border-radius:12px; background:rgba(234,84,85,0.08); border:1px solid rgba(234,84,85,0.15); margin-bottom:24px;">
                <svg width="18" height="18" fill="none" stroke="#ea5455" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span style="font-size:0.8125rem; color:#ea5455;">{{ $errors->first() }}</span>
            </div>
        @endif

        <form method="POST" action="{{ route('login.post') }}">
            @csrf

            <div style="margin-bottom:20px;">
                <label for="email" style="display:block; font-size:0.8125rem; font-weight:600; color:#8899a8; margin-bottom:8px;">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="email"
                       style="width:100%; background:#0f1923; border:1px solid rgba(255,255,255,0.12); border-radius:12px; padding:12px 16px; font-size:0.875rem; color:#e2e8f0; outline:none; transition:border-color 0.2s, box-shadow 0.2s;"
                       onfocus="this.style.borderColor='#696cff'; this.style.boxShadow='0 0 0 3px rgba(105,108,255,0.25)';"
                       onblur="this.style.borderColor='rgba(255,255,255,0.12)'; this.style.boxShadow='none';"
                       placeholder="admin@example.com" />
            </div>

            <div style="margin-bottom:28px;">
                <label for="password" style="display:block; font-size:0.8125rem; font-weight:600; color:#8899a8; margin-bottom:8px;">Password</label>
                <input id="password" type="password" name="password" required autocomplete="current-password"
                       style="width:100%; background:#0f1923; border:1px solid rgba(255,255,255,0.12); border-radius:12px; padding:12px 16px; font-size:0.875rem; color:#e2e8f0; outline:none; transition:border-color 0.2s, box-shadow 0.2s;"
                       onfocus="this.style.borderColor='#696cff'; this.style.boxShadow='0 0 0 3px rgba(105,108,255,0.25)';"
                       onblur="this.style.borderColor='rgba(255,255,255,0.12)'; this.style.boxShadow='none';"
                       placeholder="••••••••" />
            </div>

            <button type="submit"
                    style="width:100%; padding:12px; background:linear-gradient(135deg, #696cff, #5f61e6); color:#fff; font-weight:700; font-size:0.875rem; border:none; border-radius:12px; cursor:pointer; transition:all 0.2s; box-shadow:0 4px 15px rgba(105,108,255,0.3);"
                    onmouseover="this.style.boxShadow='0 6px 25px rgba(105,108,255,0.45)'; this.style.transform='translateY(-1px)';"
                    onmouseout="this.style.boxShadow='0 4px 15px rgba(105,108,255,0.3)'; this.style.transform='translateY(0)';">
                Sign In
            </button>
        </form>
    </div>

    <p style="text-align:center; font-size:0.75rem; color:#5a6f80; margin-top:24px;">
        © {{ date('Y') }} AndroidPlayer4K — All rights reserved
    </p>
</div>

</body>
</html>
