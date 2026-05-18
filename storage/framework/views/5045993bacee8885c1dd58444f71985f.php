<!DOCTYPE html>
<html lang="id" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $__env->yieldContent('title', 'Money Tracker'); ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        bg: '#0f1117',
                        surface: '#1a1d2e',
                        surface2: '#252840',
                        accent: '#6366f1',
                        accent2: '#818cf8',
                        success: '#22c55e',
                        danger: '#ef4444',
                        warning: '#f59e0b',
                        text: '#e2e8f0',
                        text2: '#94a3b8',
                    }
                }
            }
        }
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        * { font-family: 'Inter', sans-serif; }
        body { background-color: #0f1117; color: #e2e8f0; }
        .card { background: linear-gradient(135deg, #1a1d2e 0%, #252840 100%); border: 1px solid rgba(99, 102, 241, 0.1); }
        .card:hover { border-color: rgba(99, 102, 241, 0.3); }
        .btn-primary { background: linear-gradient(135deg, #6366f1 0%, #818cf8 100%); }
        .btn-primary:hover { background: linear-gradient(135deg, #4f46e5 0%, #6366f1 100%); }
        .sidebar-link { transition: all 0.2s; }
        .sidebar-link:hover, .sidebar-link.active { background: rgba(99, 102, 241, 0.15); color: #818cf8; }
        .glow { box-shadow: 0 0 20px rgba(99, 102, 241, 0.15); }
        /* Scrollbar */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #0f1117; }
        ::-webkit-scrollbar-thumb { background: #252840; border-radius: 3px; }
        ::-webkit-scrollbar-thumb:hover { background: #6366f1; }
    </style>
    <?php echo $__env->yieldPushContent('styles'); ?>
</head>
<body class="min-h-screen bg-bg text-text" x-data="{ sidebarOpen: false }">
    <!-- Mobile sidebar backdrop -->
    <div x-show="sidebarOpen" x-transition.opacity class="fixed inset-0 bg-black/60 z-40 lg:hidden" @click="sidebarOpen = false"></div>

    <!-- Sidebar -->
    <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" class="fixed top-0 left-0 z-50 w-64 h-screen bg-surface border-r border-surface2 transition-transform duration-300 lg:translate-x-0">
        <div class="flex flex-col h-full">
            <!-- Logo -->
            <div class="flex items-center gap-3 px-6 py-5 border-b border-surface2">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-accent to-accent2 flex items-center justify-center text-white text-xl">💰</div>
                <div>
                    <h1 class="font-bold text-lg text-text">Money</h1>
                    <p class="text-xs text-text2">Tracker</p>
                </div>
            </div>

            <!-- Nav -->
            <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto">
                <a href="<?php echo e(route('dashboard')); ?>" class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-lg text-text2 hover:text-text <?php echo e(request()->routeIs('dashboard') ? 'active' : ''); ?>">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    <span>Dashboard</span>
                </a>
                <a href="<?php echo e(route('transactions.index')); ?>" class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-lg text-text2 hover:text-text <?php echo e(request()->routeIs('transactions.*') ? 'active' : ''); ?>">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    <span>Transaksi</span>
                </a>
                <a href="<?php echo e(route('categories.index')); ?>" class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-lg text-text2 hover:text-text <?php echo e(request()->routeIs('categories.*') ? 'active' : ''); ?>">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                    <span>Kategori</span>
                </a>
                <a href="<?php echo e(route('reports.index')); ?>" class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-lg text-text2 hover:text-text <?php echo e(request()->routeIs('reports.*') ? 'active' : ''); ?>">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    <span>Laporan</span>
                </a>
                <a href="<?php echo e(route('settings.index')); ?>" class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-lg text-text2 hover:text-text <?php echo e(request()->routeIs('settings.*') ? 'active' : ''); ?>">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    <span>Pengaturan</span>
                </a>
            </nav>

            <!-- User -->
            <div class="px-4 py-4 border-t border-surface2">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-full bg-gradient-to-br from-accent to-accent2 flex items-center justify-center text-white text-sm font-bold">
                        <?php echo e(substr(Auth::user()->name, 0, 1)); ?>

                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-text truncate"><?php echo e(Auth::user()->name); ?></p>
                        <p class="text-xs text-text2 truncate"><?php echo e(Auth::user()->email); ?></p>
                    </div>
                    <form method="POST" action="<?php echo e(route('logout')); ?>">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="text-text2 hover:text-danger transition-colors" title="Logout">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </aside>

    <!-- Main content -->
    <div class="lg:ml-64">
        <!-- Top bar -->
        <header class="sticky top-0 z-30 bg-bg/80 backdrop-blur-lg border-b border-surface2">
            <div class="flex items-center justify-between px-4 py-3 lg:px-8">
                <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden text-text2 hover:text-text">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
                <div class="flex items-center gap-3">
                    <span class="text-sm text-text2"><?php echo e(now()->format('l, d F Y')); ?></span>
                </div>
            </div>
        </header>

        <!-- Page content -->
        <main class="p-4 lg:p-8">
            <?php if(session('success')): ?>
                <div class="mb-4 p-4 rounded-lg bg-success/10 border border-success/30 text-success flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    <?php echo e(session('success')); ?>

                </div>
            <?php endif; ?>

            <?php echo $__env->yieldContent('content'); ?>
        </main>
    </div>

    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>
<?php /**PATH /var/www/money-tracker/resources/views/layouts/app.blade.php ENDPATH**/ ?>