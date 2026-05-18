<!DOCTYPE html>
<html lang="id" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Money Tracker')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        bg: '#0B0E14',
                        surface: '#151A25',
                        surface2: '#1E2532',
                        surface3: '#2A3441',
                        primary: {
                            50: '#eef2ff',
                            100: '#e0e7ff',
                            400: '#818cf8',
                            500: '#6366f1',
                            600: '#4f46e5',
                            700: '#4338ca',
                        },
                        success: '#10b981',
                        'success-light': '#34d399',
                        'success-dark': '#059669',
                        danger: '#f43f5e',
                        'danger-light': '#fb7185',
                        'danger-dark': '#e11d48',
                        warning: '#f59e0b',
                        'warning-light': '#fbbf24',
                        'warning-dark': '#d97706',
                        text: {
                            primary: '#f8fafc',
                            secondary: '#94a3b8',
                            tertiary: '#64748b',
                            muted: '#475569',
                        },
                    },
                    boxShadow: {
                        'glow-success': '0 0 20px rgba(16, 185, 129, 0.15)',
                        'glow-danger': '0 0 20px rgba(244, 63, 94, 0.15)',
                        'card': '0 4px 6px -1px rgba(0, 0, 0, 0.3)',
                        'card-hover': '0 20px 25px -5px rgba(0, 0, 0, 0.4)',
                        'button': '0 4px 14px rgba(99, 102, 241, 0.25)',
                        'button-hover': '0 6px 20px rgba(99, 102, 241, 0.4)',
                    },
                    animation: {
                        'fade-in': 'fadeIn 0.3s ease-out',
                        'slide-up': 'slideUp 0.4s ease-out',
                        'pulse-soft': 'pulseSoft 2s ease-in-out infinite',
                    },
                    keyframes: {
                        fadeIn: {
                            '0%': { opacity: '0' },
                            '100%': { opacity: '1' },
                        },
                        slideUp: {
                            '0%': { opacity: '0', transform: 'translateY(10px)' },
                            '100%': { opacity: '1', transform: 'translateY(0)' },
                        },
                        pulseSoft: {
                            '0%, 100%': { opacity: '1' },
                            '50%': { opacity: '0.7' },
                        },
                    },
                },
            }
        }
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        * { font-family: 'Inter', sans-serif; }
        body { background-color: #0B0E14; color: #f8fafc; }

        /* Custom Scrollbar */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: #0B0E14; }
        ::-webkit-scrollbar-thumb { background: #2A3441; border-radius: 3px; }
        ::-webkit-scrollbar-thumb:hover { background: #475569; }

        /* Selection */
        ::selection { background: rgba(99, 102, 241, 0.3); color: #f8fafc; }

        /* Animations */
        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
        @keyframes slideUp { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
        @keyframes pulseSoft { 0%, 100% { opacity: 1; } 50% { opacity: 0.7; } }
        @keyframes shimmer { 0% { background-position: -200% 0; } 100% { background-position: 200% 0; } }

        .animate-fade-in { animation: fadeIn 0.3s ease-out; }
        .animate-slide-up { animation: slideUp 0.4s ease-out; }
        .animate-pulse-soft { animation: pulseSoft 2s ease-in-out infinite; }

        /* Sidebar link styles */
        .sidebar-link { transition: all 0.2s; }
        .sidebar-link:hover, .sidebar-link.active {
            background: rgba(99, 102, 241, 0.15);
            color: #818cf8;
        }

        /* Card hover */
        .card {
            background: linear-gradient(135deg, #151A25 0%, #1E2532 100%);
            border: 1px solid rgba(42, 52, 65, 0.5);
            transition: all 0.3s;
        }
        .card:hover {
            border-color: rgba(99, 102, 241, 0.3);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.4);
            transform: translateY(-2px);
        }

        /* Button primary */
        .btn-primary {
            background: linear-gradient(135deg, #6366f1 0%, #818cf8 100%);
            box-shadow: 0 4px 14px rgba(99, 102, 241, 0.25);
            transition: all 0.2s;
        }
        .btn-primary:hover {
            background: linear-gradient(135deg, #4f46e5 0%, #6366f1 100%);
            box-shadow: 0 6px 20px rgba(99, 102, 241, 0.4);
            transform: translateY(-1px);
        }

        /* Glow effects */
        .glow-primary { box-shadow: 0 0 20px rgba(99, 102, 241, 0.15); }
        .glow-success { box-shadow: 0 0 20px rgba(16, 185, 129, 0.15); }
        .glow-danger { box-shadow: 0 0 20px rgba(244, 63, 94, 0.15); }

        /* Toast animation */
        .toast-enter { animation: slideUp 0.4s ease-out; }
        .toast-leave { animation: fadeIn 0.2s ease-out reverse; }

        /* x-cloak */
        [x-cloak] { display: none !important; }
    </style>
    @stack('styles')
</head>
<body class="min-h-screen bg-bg text-text-primary" x-data="{ sidebarOpen: false }">

    <!-- Ambient Background Glow (fixed, pointer-events-none) -->
    <div class="fixed inset-0 pointer-events-none overflow-hidden z-0">
        <div class="absolute top-0 right-0 w-[600px] h-[600px] bg-gradient-to-bl from-primary-500/5 to-transparent rounded-full -mr-60 -mt-60"></div>
        <div class="absolute bottom-0 left-0 w-[400px] h-[400px] bg-gradient-to-tr from-success/3 to-transparent rounded-full -ml-40 -mb-40"></div>
    </div>

    <!-- Mobile sidebar backdrop -->
    <div x-show="sidebarOpen" x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-black/60 backdrop-blur-sm z-40 lg:hidden"
         @click="sidebarOpen = false">
    </div>

    <!-- Sidebar -->
    <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
           class="fixed top-0 left-0 z-50 w-64 h-screen bg-surface border-r border-surface3/30 transition-transform duration-300 lg:translate-x-0 flex flex-col">

        <!-- Logo -->
        <div class="flex items-center gap-3 px-6 py-5 border-b border-surface3/30">
            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-primary-500 to-primary-600 flex items-center justify-center text-white text-xl shadow-button">💰</div>
            <div>
                <h1 class="font-bold text-lg text-text-primary">Money</h1>
                <p class="text-xs text-text-secondary">Tracker</p>
            </div>
        </div>

        <!-- Nav -->
        <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto">
            <a href="{{ route('dashboard') }}"
               class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-xl text-text-secondary hover:text-text-primary {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                <span class="font-medium">Dashboard</span>
            </a>
            <a href="{{ route('transactions.index') }}"
               class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-xl text-text-secondary hover:text-text-primary {{ request()->routeIs('transactions.*') ? 'active' : '' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                <span class="font-medium">Transaksi</span>
            </a>
            <a href="{{ route('categories.index') }}"
               class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-xl text-text-secondary hover:text-text-primary {{ request()->routeIs('categories.*') ? 'active' : '' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                <span class="font-medium">Kategori</span>
            </a>
            <a href="{{ route('reports.index') }}"
               class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-xl text-text-secondary hover:text-text-primary {{ request()->routeIs('reports.*') ? 'active' : '' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                <span class="font-medium">Laporan</span>
            </a>
            <a href="{{ route('settings.index') }}"
               class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-xl text-text-secondary hover:text-text-primary {{ request()->routeIs('settings.*') ? 'active' : '' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                <span class="font-medium">Pengaturan</span>
            </a>
        </nav>

        <!-- User -->
        <div class="px-3 py-2.5 border-t border-surface3/30">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-xl bg-gradient-to-br from-primary-500 to-primary-600 flex items-center justify-center text-white text-xs font-bold shadow-button flex-shrink-0">
                    {{ substr(Auth::user()->name, 0, 1) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-text-primary truncate">{{ Auth::user()->name }}</p>
                </div>
                <a href="{{ route('logout.get') }}"
                   class="flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg text-text-tertiary hover:text-danger hover:bg-danger/10 transition-colors text-xs font-medium"
                   title="Logout"
                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    Keluar
                </a>
                <form id="logout-form" method="GET" action="{{ route('logout.get') }}" class="hidden"></form>
            </div>
        </div>
    </aside>

    <!-- Main content -->
    <div class="lg:ml-64 relative z-10">
        <!-- Top bar -->
        <header class="sticky top-0 z-30 bg-bg/80 backdrop-blur-xl border-b border-surface3/30">
            <div class="flex items-center justify-between px-4 py-3 lg:px-8">
                <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden text-text-secondary hover:text-text-primary transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
                <div class="flex items-center gap-3">
                    <span class="text-sm text-text-secondary">{{ now()->format('l, d F Y') }}</span>
                </div>
            </div>
        </header>

        <!-- Page content -->
        <main class="p-4 lg:p-8">

            <!-- Flash Messages (Toast) -->
            @if(session('success'))
            <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 4000)"
                 x-show="show"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-2"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0"
                 x-transition:leave-end="opacity-0 -translate-y-2"
                 class="mb-6 flex items-center gap-3 p-4 rounded-xl bg-success/10 border border-success/20 text-success animate-slide-up">
                <div class="w-8 h-8 rounded-lg bg-success/20 flex items-center justify-center flex-shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                </div>
                <p class="text-sm font-medium">{{ session('success') }}</p>
            </div>
            @endif

            @if(session('error'))
            <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 4000)"
                 x-show="show"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-2"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 class="mb-6 flex items-center gap-3 p-4 rounded-xl bg-danger/10 border border-danger/20 text-danger animate-slide-up">
                <div class="w-8 h-8 rounded-lg bg-danger/20 flex items-center justify-center flex-shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </div>
                <p class="text-sm font-medium">{{ session('error') }}</p>
            </div>
            @endif

            @yield('content')
        </main>
    </div>

    @stack('scripts')
</body>
</html>
