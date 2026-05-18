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
    <style>* { font-family: 'Inter', sans-serif; } body { background-color: #0f1117; color: #e2e8f0; }</style>
</head>
<body class="min-h-screen bg-bg text-text flex items-center justify-center p-4">
    <div class="w-full max-w-md">
        <div class="text-center mb-8">
            <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-accent to-accent2 flex items-center justify-center text-3xl mx-auto mb-4">💰</div>
            <h1 class="text-2xl font-bold">Money Tracker</h1>
            <p class="text-text2 text-sm mt-1">Kelola keuangan dengan mudah</p>
        </div>

        <div class="card rounded-2xl p-6" style="background: linear-gradient(135deg, #1a1d2e 0%, #252840 100%); border: 1px solid rgba(99, 102, 241, 0.1);">
            @yield('content')
        </div>
    </div>
</body>
</html>
