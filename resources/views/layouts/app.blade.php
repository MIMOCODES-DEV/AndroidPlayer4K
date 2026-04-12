<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>AndroidPlayer4K Admin</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        * { box-sizing: border-box; }
        body { font-family: 'Inter', system-ui, -apple-system, sans-serif; }
        :root {
            --bg-body: #fcfcfc;
            --bg-sidebar: #fff;
            --bg-card: #fff;
            --bg-input: #fff;
            --border: #e8e8e8;
            --border-input: #d3d4d5;
            --text-primary: #23272f;
            --text-secondary: #566a7f;
            --text-muted: #a1acb8;
            --accent: #2563eb;
            --accent-hover: #1d4ed8;
            --accent-glow: rgba(37,99,235,0.12);
            --success: #28c76f;
            --danger: #ea5455;
            --warning: #ff9f43;
        }
        body.dark-mode {
            --bg-body: #0f1923;
            --bg-sidebar: #162231;
            --bg-card: #1a2d3f;
            --bg-input: #0d2035;
            --border: rgba(255,255,255,0.09);
            --border-input: rgba(255,255,255,0.22);
            --text-primary: #f8fafc;
            --text-secondary: #b6c2d1;
            --text-muted: #8fa6be;
            --accent: #2563eb;
            --accent-hover: #1d4ed8;
            --accent-glow: rgba(37,99,235,0.25);
        }
        #sidebar { transition: transform 0.3s cubic-bezier(0.4,0,0.2,1); }
        @media (max-width: 1023px) {
            #sidebar { position:fixed; top:0; left:0; bottom:0; z-index:40; transform:translateX(-100%); }
            #sidebar.sidebar-open { transform:translateX(0); }
        }
        @media (min-width: 1024px) { #overlay { display:none !important; } }

        /* Input styles */
        .ap-input {
            width:100%; background:var(--bg-input); border:1px solid var(--border-input);
            border-radius:10px; padding:10px 16px; font-size:0.875rem; color:var(--text-primary);
            outline:none; transition:border-color 0.2s, box-shadow 0.2s;
        }
        .ap-input:focus { border-color:var(--accent); box-shadow:0 0 0 3px var(--accent-glow); }
        .ap-input::placeholder { color:var(--text-muted); }
        .ap-input[type="file"] { color:var(--text-secondary); }

        /* Select */
        .ap-select {
            width:100%; background:var(--bg-input); border:1px solid var(--border-input);
            border-radius:10px; padding:10px 16px; font-size:0.875rem; color:var(--text-primary);
            outline:none; transition:border-color 0.2s, box-shadow 0.2s; appearance:auto;
        }
        .ap-select:focus { border-color:var(--accent); box-shadow:0 0 0 3px var(--accent-glow); }
        .ap-select option { background:var(--bg-card); color:var(--text-primary); }

        /* Label */
        .ap-label { display:block; font-size:0.8125rem; font-weight:600; color:var(--text-secondary); margin-bottom:6px; }

        /* Card */
        .ap-card { background:var(--bg-card); border:1px solid var(--border); border-radius:14px; padding:24px; }

        /* Section header */
        .ap-section-title {
            font-size:0.8125rem; font-weight:700; text-transform:uppercase; letter-spacing:0.08em;
            color:var(--text-muted); margin-bottom:20px; padding-bottom:12px;
            border-bottom:1px solid var(--border);
        }

        /* Button primary */
        .ap-btn {
            display:inline-flex; align-items:center; gap:8px; padding:10px 24px;
            background:var(--accent); color:#fff; font-weight:600; font-size:0.875rem;
            border-radius:10px; border:none; cursor:pointer;
            transition:all 0.2s; box-shadow:0 4px 15px rgba(105,108,255,0.3);
        }
        .ap-btn:hover { background:var(--accent-hover); box-shadow:0 6px 20px rgba(105,108,255,0.4); transform:translateY(-1px); }

        .ap-btn-outline {
            display:inline-flex; align-items:center; gap:8px; padding:10px 24px;
            background:transparent; color:var(--text-secondary); font-weight:600; font-size:0.875rem;
            border-radius:10px; border:1px solid var(--border-input); cursor:pointer;
            transition:all 0.2s;
        }
        .ap-btn-outline:hover { border-color:var(--accent); color:var(--accent); }

        .ap-btn-danger {
            display:inline-flex; align-items:center; gap:4px; padding:6px 14px;
            background:rgba(234,84,85,0.1); color:var(--danger); font-weight:600; font-size:0.75rem;
            border-radius:8px; border:1px solid rgba(234,84,85,0.2); cursor:pointer; transition:all 0.2s;
        }
        .ap-btn-danger:hover { background:rgba(234,84,85,0.2); border-color:rgba(234,84,85,0.4); }

        .ap-btn-edit {
            display:inline-flex; align-items:center; gap:4px; padding:6px 14px;
            background:rgba(37,99,235,0.1); color:var(--accent); font-weight:600; font-size:0.75rem;
            border-radius:8px; border:1px solid rgba(37,99,235,0.3); cursor:pointer; transition:all 0.2s;
        }
        .ap-btn-edit:hover { background:rgba(37,99,235,0.22); border-color:rgba(37,99,235,0.55); }
        /* Product tabs dark mode */
        body.dark-mode .product-tab-btn { color:#b6c2d1 !important; border-color:rgba(255,255,255,0.18) !important; background:transparent !important; }
        body.dark-mode .product-tab-btn.tab-active { color:#ffffff !important; border-color:var(--accent) !important; background:rgba(37,99,235,0.18) !important; }
        body.dark-mode .product-tab-btn:hover { color:#ffffff !important; border-color:var(--accent) !important; }
        body.dark-mode .ap-btn-edit { background:rgba(37,99,235,0.22); color:#ffffff; border-color:rgba(37,99,235,0.55); }
        body.dark-mode .ap-btn-edit:hover { background:rgba(37,99,235,0.38); border-color:#2563eb; }
        .domain-url-link { color:var(--accent); }
        body.dark-mode .domain-url-link { color:#90c4ff; }
        body.dark-mode .ap-btn-danger { background:rgba(234,84,85,0.18); border-color:rgba(234,84,85,0.45); }
        body.dark-mode .ap-btn-danger:hover { background:rgba(234,84,85,0.3); border-color:rgba(234,84,85,0.7); }

        /* Badge */
        .ap-badge { display:inline-block; padding:4px 12px; border-radius:20px; font-size:0.75rem; font-weight:600; }
        .ap-badge-success { background:rgba(40,199,111,0.12); color:var(--success); }
        .ap-badge-muted { background:rgba(255,255,255,0.05); color:var(--text-muted); }
        .ap-badge-accent { background:rgba(105,108,255,0.12); color:var(--accent); font-family:monospace; }

        /* Table */
        .ap-table { width:100%; border-collapse:collapse; }
        .ap-table thead th { padding:14px 20px; font-size:0.75rem; font-weight:700; text-transform:uppercase; letter-spacing:0.06em; color:var(--text-muted); text-align:left; border-bottom:1px solid var(--border); }
        .ap-table tbody td { padding:16px 20px; font-size:0.875rem; color:var(--text-secondary); border-bottom:1px solid var(--border); }
        .ap-table tbody tr { transition:background 0.15s; }
        .ap-table tbody tr:hover { background:rgba(105,108,255,0.04); }
        .ap-table tbody tr:last-child td { border-bottom:none; }
        body.dark-mode .ap-table thead th { color:#a8c0d6; border-bottom-color:rgba(255,255,255,0.1); }
        body.dark-mode .ap-table tbody td { color:#d0dce8; border-bottom-color:rgba(255,255,255,0.07); }
        body.dark-mode .ap-table tbody tr:hover { background:rgba(37,99,235,0.08); }
        body.dark-mode .ap-badge-accent { background:rgba(37,99,235,0.2); color:#7eb3ff; }
        body.dark-mode .ap-badge-muted { background:rgba(255,255,255,0.08); color:#a8c0d6; }

        /* Scrollbar */
        ::-webkit-scrollbar { width:6px; height:6px; }
        ::-webkit-scrollbar-track { background:transparent; }
        ::-webkit-scrollbar-thumb { background:rgba(255,255,255,0.1); border-radius:3px; }
        ::-webkit-scrollbar-thumb:hover { background:rgba(255,255,255,0.2); }

        /* Nav link */
        .nav-link { display:flex; align-items:center; gap:12px; padding:10px 14px; border-radius:10px; font-size:0.875rem; font-weight:500; color:var(--text-secondary); transition:all 0.2s; text-decoration:none; }
        .nav-link:hover { background:rgba(105,108,255,0.08); color:var(--text-primary); }
        .nav-link.active { background:rgba(105,108,255,0.15); color:var(--accent); }
        .nav-link .nav-icon { width:20px; height:20px; shrink:0; opacity:0.7; }
        .nav-link.active .nav-icon { opacity:1; }

        /* Flash animation */
        @keyframes slideDown { from { opacity:0; transform:translateY(-8px); } to { opacity:1; transform:translateY(0); } }
        .flash-msg { animation:slideDown 0.3s ease-out; }
    </style>
</head>
<body id="ap-body" style="background:var(--bg-body); color:var(--text-primary); margin:0;">

{{-- Mobile overlay --}}
<div id="overlay" class="hidden fixed inset-0 z-30" style="background:rgba(0,0,0,0.6); backdrop-filter:blur(4px);" onclick="closeSidebar()"></div>

<div class="flex h-screen" style="overflow:hidden;">

    {{-- ─── Sidebar ───────────────────────────────────────────────────────── --}}
    <aside id="sidebar" class="w-65 flex flex-col shrink-0 h-full" style="background:var(--bg-sidebar); border-right:1px solid var(--border);">

        {{-- Logo --}}
        <div class="px-6 py-5 flex items-center justify-between" style="border-bottom:1px solid var(--border);">
            <div class="flex items-center gap-3">
                <div style="width:36px; height:36px; background:linear-gradient(135deg, #2563eb, #1d4ed8); border-radius:10px; display:flex; align-items:center; justify-content:center;">
                    <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="white" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/><path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <div style="font-size:1rem; font-weight:700; color:var(--text-primary);">Android<span style="color:var(--accent);">Player4K</span></div>
                    <div style="font-size:0.65rem; color:var(--text-muted); text-transform:uppercase; letter-spacing:0.1em;">Admin Panel</div>
                </div>
            </div>
            <button onclick="closeSidebar()" class="lg:hidden p-1 rounded-lg" style="color:var(--text-muted);">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        {{-- Navigation --}}
        <nav class="flex-1 px-3 py-4 overflow-y-auto">
            <div style="font-size:0.65rem; font-weight:700; text-transform:uppercase; letter-spacing:0.12em; color:var(--text-muted); padding:8px 14px 12px;">
                App Management
            </div>

            @php $navItems = [
                ['route' => 'products.index',        'label' => 'Manage Products', 'pattern' => 'products*',        'icon' => 'M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10'],
                ['route' => 'domain-urls.index',     'label' => 'Domain URL',      'pattern' => 'domain-urls*',     'icon' => 'M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1'],
                ['route' => 'contact-details.index', 'label' => 'Contact Details', 'pattern' => 'contact-details*', 'icon' => 'M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z'],
                ['route' => 'versions.index',        'label' => 'Update Version',  'pattern' => 'versions*',        'icon' => 'M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4'],
            ];
            @endphp
            @foreach($navItems as $item)
                @php $active = request()->is($item['pattern']); @endphp
                <a href="{{ route($item['route']) }}" onclick="closeSidebar()"
                   class="nav-link {{ $active ? 'active' : '' }}">
                    <svg class="nav-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="{{ $item['icon'] }}"/>
                    </svg>
                    {{ $item['label'] }}
                </a>
            @endforeach

        </nav>

        {{-- Logout at bottom of sidebar --}}
        <div style="flex:1 1 0%; display:flex; flex-direction:column; justify-content:flex-end;">
            <form method="POST" action="{{ route('logout') }}" style="width:100%; padding:1.5rem 1rem 1.5rem 1rem;">
                @csrf
                <button type="submit" class="nav-link" style="width:100%; background:rgba(234,84,85,0.12); color:var(--danger); border:none; display:flex; align-items:center; gap:10px; font-weight:600; justify-content:center; border-radius:12px; padding:12px 0; font-size:1rem; transition:background 0.2s; cursor:pointer;"
                    onmouseover="this.style.background='rgba(234,84,85,0.22)'; this.style.cursor='pointer';" onmouseout="this.style.background='rgba(234,84,85,0.12)'; this.style.cursor='pointer';">
                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    Logout
                </button>
            </form>
        </div>
    </aside>

    {{-- ─── Main content ──────────────────────────────────────────────────── --}}
    <div class="flex-1 flex flex-col min-w-0" style="overflow:hidden;">

        {{-- Top bar --}}
        <header class="shrink-0 flex items-center justify-between gap-3 px-4 lg:px-8 py-6.5" style="background:var(--bg-sidebar); border-bottom:1px solid var(--border);">
            <div class="flex items-center gap-3">
                <button onclick="toggleSidebar()" class="lg:hidden p-2 rounded-lg" style="color:var(--text-secondary); background:rgba(255,255,255,0.05);" aria-label="Menu">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
                <h1 style="font-size:1.125rem; font-weight:700; color:var(--text-primary);">@yield('page-title', 'Dashboard')</h1>
                @hasSection('page-subtitle')
                    <span style="font-size:0.8125rem; color:var(--text-muted); margin-left:4px;">@yield('page-subtitle')</span>
                @endif
            </div>
            <div></div>
        </header>

        {{-- Flash messages --}}
        @if(session('success') || session('error'))
        <div class="px-4 lg:px-8 pt-5">
            @if(session('success'))
                <div class="flash-msg ap-card flex items-center gap-3" style="padding:14px 20px; border-left:4px solid var(--success); margin-bottom:12px;">
                    <svg width="20" height="20" fill="none" stroke="var(--success)" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span style="font-size:0.875rem; color:var(--success);">{{ session('success') }}</span>
                </div>
            @endif
            @if(session('error'))
                <div class="flash-msg ap-card flex items-center gap-3" style="padding:14px 20px; border-left:4px solid var(--danger); margin-bottom:12px;">
                    <svg width="20" height="20" fill="none" stroke="var(--danger)" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span style="font-size:0.875rem; color:var(--danger);">{{ session('error') }}</span>
                </div>
            @endif
        </div>
        @endif

        {{-- Page content --}}
        <main class="flex-1 px-4 lg:px-8 py-6" style="overflow-y:auto; overflow-x:auto;">
            @yield('content')
        </main>
      
        {{-- Footer --}}
        <footer class="shrink-0" style="border-top:1px solid var(--border); font-size:0.75rem; color:var(--text-muted); padding: 12px 24px 8px 24px;">
            <div style="width:100%; display:flex; justify-content:space-between; align-items:center;">
                <span>© {{ date('Y') }} Copyright Created By <strong style="color:var(--text-secondary);">AndroidPlayer4K</strong></span>
                <button id="theme-toggle" onclick="toggleTheme()" title="Toggle dark/light mode" style="background:var(--bg-card); border:1px solid var(--border-input); border-radius:8px; padding:7px 13px; color:var(--accent); font-weight:600; font-size:0.9rem; cursor:pointer; transition:all 0.2s;">
                    <span id="theme-toggle-icon" style="display:inline-block; vertical-align:middle;">
                        <svg id="theme-sun" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="5"/><path d="M12 1v2m0 18v2m11-11h-2M3 12H1m16.95 6.95l-1.414-1.414M6.343 6.343L4.929 4.929m12.02 0l-1.414 1.414M6.343 17.657l-1.414 1.414"/></svg>
                        <svg id="theme-moon" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 12.79A9 9 0 1111.21 3a7 7 0 109.79 9.79z"/></svg>
                    </span>
                </button>
            </div>
        </footer>
    </div>
</div>


<script>
    function toggleSidebar() {
        document.getElementById('sidebar').classList.add('sidebar-open');
        document.getElementById('overlay').classList.remove('hidden');
    }
    function closeSidebar() {
        document.getElementById('sidebar').classList.remove('sidebar-open');
        document.getElementById('overlay').classList.add('hidden');
    }
    // Theme toggle logic
    function setTheme(mode) {
        const body = document.getElementById('ap-body');
        const sun = document.getElementById('theme-sun');
        const moon = document.getElementById('theme-moon');
        if (mode === 'dark') {
            body.classList.add('dark-mode');
            sun.style.display = 'none';
            moon.style.display = 'inline';
        } else {
            body.classList.remove('dark-mode');
            sun.style.display = 'inline';
            moon.style.display = 'none';
        }
        localStorage.setItem('ap-theme', mode);
    }
    function toggleTheme() {
        const current = document.getElementById('ap-body').classList.contains('dark-mode') ? 'dark' : 'light';
        setTheme(current === 'dark' ? 'light' : 'dark');
    }
    // On load, set theme from localStorage
    document.addEventListener('DOMContentLoaded', function() {
        let mode = localStorage.getItem('ap-theme');
        if (!mode) mode = 'light';
        setTheme(mode);
    });
</script>

</body>
</html>
