@extends('layouts.app')
@section('title', 'Dashboard - Money Tracker')

@section('content')
<div class="space-y-6 animate-fade-in">

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">

        <!-- Balance Card -->
        <div class="relative overflow-hidden rounded-2xl bg-surface border border-surface3/50 p-6 shadow-card hover:shadow-card-hover transition-all duration-300 group hover:-translate-y-0.5">
            <div class="absolute top-0 right-0 w-40 h-40 bg-gradient-to-bl from-primary-500/10 to-transparent rounded-full -mr-20 -mt-20 group-hover:from-primary-500/20 transition-colors duration-500"></div>
            <div class="relative">
                <div class="flex items-center gap-3 mb-4">
                    <div class="p-2.5 rounded-xl bg-primary-500/10 ring-1 ring-primary-500/20">
                        <svg class="w-5 h-5 text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <span class="text-sm font-medium text-text-secondary">Saldo Bulan Ini</span>
                </div>
                <p class="text-3xl font-bold text-text-primary tracking-tight">Rp{{ number_format($month['balance'], 0, ',', '.') }}</p>
                <div class="flex items-center gap-2 mt-2">
                    <span class="px-2 py-0.5 rounded-full text-xs bg-primary-500/10 text-primary-400 font-medium">{{ $month['count'] }} transaksi</span>
                </div>
            </div>
        </div>

        <!-- Income Card -->
        <div class="relative overflow-hidden rounded-2xl bg-surface border border-surface3/50 p-6 shadow-card hover:shadow-card-hover transition-all duration-300 group hover:-translate-y-0.5">
            <div class="absolute top-0 right-0 w-40 h-40 bg-gradient-to-bl from-success/10 to-transparent rounded-full -mr-20 -mt-20 group-hover:from-success/20 transition-colors duration-500"></div>
            <div class="relative">
                <div class="flex items-center gap-3 mb-4">
                    <div class="p-2.5 rounded-xl bg-success/10 ring-1 ring-success/20">
                        <svg class="w-5 h-5 text-success" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12"/></svg>
                    </div>
                    <span class="text-sm font-medium text-text-secondary">Pemasukan</span>
                </div>
                <p class="text-3xl font-bold text-success tracking-tight">Rp{{ number_format($month['income'], 0, ',', '.') }}</p>
                <div class="mt-2">
                    <span class="text-xs text-text-tertiary">Bulan {{ now()->format('F') }}</span>
                </div>
            </div>
        </div>

        <!-- Expense Card -->
        <div class="relative overflow-hidden rounded-2xl bg-surface border border-surface3/50 p-6 shadow-card hover:shadow-card-hover transition-all duration-300 group hover:-translate-y-0.5">
            <div class="absolute top-0 right-0 w-40 h-40 bg-gradient-to-bl from-danger/10 to-transparent rounded-full -mr-20 -mt-20 group-hover:from-danger/20 transition-colors duration-500"></div>
            <div class="relative">
                <div class="flex items-center gap-3 mb-4">
                    <div class="p-2.5 rounded-xl bg-danger/10 ring-1 ring-danger/20">
                        <svg class="w-5 h-5 text-danger" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 13l-5 5m0 0l-5-5m5 5V6"/></svg>
                    </div>
                    <span class="text-sm font-medium text-text-secondary">Pengeluaran</span>
                </div>
                <p class="text-3xl font-bold text-danger tracking-tight">Rp{{ number_format($month['expense'], 0, ',', '.') }}</p>
                <div class="mt-2">
                    <span class="text-xs text-text-tertiary">Bulan {{ now()->format('F') }}</span>
                </div>
            </div>
        </div>

        <!-- Today Card -->
        <div class="relative overflow-hidden rounded-2xl bg-surface border border-surface3/50 p-6 shadow-card hover:shadow-card-hover transition-all duration-300 group hover:-translate-y-0.5">
            <div class="absolute top-0 right-0 w-40 h-40 bg-gradient-to-bl from-warning/10 to-transparent rounded-full -mr-20 -mt-20 group-hover:from-warning/20 transition-colors duration-500"></div>
            <div class="relative">
                <div class="flex items-center gap-3 mb-4">
                    <div class="p-2.5 rounded-xl bg-warning/10 ring-1 ring-warning/20">
                        <svg class="w-5 h-5 text-warning" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                    <span class="text-sm font-medium text-text-secondary">Hari Ini</span>
                </div>
                <p class="text-3xl font-bold {{ $today['balance'] >= 0 ? 'text-success' : 'text-danger' }} tracking-tight">
                    Rp{{ number_format($today['balance'], 0, ',', '.') }}
                </p>
                <div class="mt-2">
                    <span class="text-xs text-text-tertiary">{{ $today['count'] }} transaksi</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Weekly Chart -->
        <div class="lg:col-span-2 relative overflow-hidden rounded-2xl bg-surface border border-surface3/50 p-6 shadow-card">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-lg font-semibold text-text-primary">📊 Tren 7 Hari Terakhir</h3>
                    <p class="text-xs text-text-tertiary mt-1">Perbandingan pemasukan dan pengeluaran</p>
                </div>
                <div class="flex items-center gap-4 text-xs">
                    <div class="flex items-center gap-1.5">
                        <div class="w-3 h-3 rounded-full bg-success"></div>
                        <span class="text-text-secondary">Pemasukan</span>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <div class="w-3 h-3 rounded-full bg-danger"></div>
                        <span class="text-text-secondary">Pengeluaran</span>
                    </div>
                </div>
            </div>
            <canvas id="weeklyChart" height="120"></canvas>
        </div>

        <!-- Category Pie -->
        <div class="relative overflow-hidden rounded-2xl bg-surface border border-surface3/50 p-6 shadow-card">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-lg font-semibold text-text-primary">🍩 Kategori</h3>
                    <p class="text-xs text-text-tertiary mt-1">Distribusi pengeluaran</p>
                </div>
            </div>
            <canvas id="categoryChart" height="160"></canvas>
            <div class="mt-4 space-y-2">
                @forelse($categoryBreakdown->take(4) as $cat)
                <div class="flex items-center justify-between text-sm">
                    <div class="flex items-center gap-2">
                        <div class="w-2.5 h-2.5 rounded-full" style="background-color: {{ $cat['color'] }}"></div>
                        <span class="text-text-secondary">{{ $cat['icon'] }} {{ $cat['name'] }}</span>
                    </div>
                    <span class="text-text-primary font-medium">Rp{{ number_format($cat['total'], 0, ',', '.') }}</span>
                </div>
                @empty
                <p class="text-sm text-text-muted text-center py-4">Belum ada data kategori</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Recent Transactions -->
    <div class="relative overflow-hidden rounded-2xl bg-surface border border-surface3/50 p-6 shadow-card">
        <div class="flex items-center justify-between mb-5">
            <div>
                <h3 class="text-lg font-semibold text-text-primary">📋 Transaksi Terakhir</h3>
                <p class="text-xs text-text-tertiary mt-1">Aktivitas terbaru kamu</p>
            </div>
            <a href="{{ route('transactions.index') }}" class="text-sm text-primary-400 hover:text-primary-300 font-medium transition-colors">Lihat semua →</a>
        </div>

        @if($recentTransactions->isEmpty())
            <div class="text-center py-12">
                <div class="w-16 h-16 rounded-2xl bg-surface2 mx-auto mb-4 flex items-center justify-center text-3xl">📭</div>
                <p class="text-text-secondary mb-4">Belum ada transaksi</p>
                <a href="{{ route('transactions.create') }}" class="btn-primary inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-white text-sm font-medium">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Tambah Transaksi
                </a>
            </div>
        @else
            <div class="space-y-2">
                @foreach($recentTransactions as $t)
                <div class="flex items-center justify-between p-3 rounded-xl hover:bg-surface2/50 transition-colors duration-150 group">
                    <div class="flex items-center gap-3">
                        <div class="w-11 h-11 rounded-xl flex items-center justify-center text-xl transition-transform group-hover:scale-105
                            {{ $t->type === 'income' ? 'bg-success/10 ring-1 ring-success/20' : 'bg-danger/10 ring-1 ring-danger/20' }}">
                            {{ $t->category?->icon ?? '📦' }}
                        </div>
                        <div>
                            <p class="text-sm font-medium text-text-primary">{{ $t->description }}</p>
                            <div class="flex items-center gap-2 mt-0.5">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-lg text-xs
                                    {{ $t->type === 'income' ? 'bg-success/10 text-success' : 'bg-danger/10 text-danger' }} font-medium">
                                    {{ $t->type === 'income' ? 'Pemasukan' : 'Pengeluaran' }}
                                </span>
                                <span class="text-xs text-text-muted">{{ $t->category?->name ?? '-' }} • {{ $t->transaction_date->format('d M Y') }}</span>
                            </div>
                        </div>
                    </div>
                    <span class="font-semibold {{ $t->type === 'income' ? 'text-success' : 'text-danger' }}">
                        {{ $t->type === 'income' ? '+' : '-' }}Rp{{ number_format($t->amount, 0, ',', '.') }}
                    </span>
                </div>
                @endforeach
            </div>
        @endif
    </div>

</div>
@endsection

@push('scripts')
<script>
    // Chart.js Global Defaults
    Chart.defaults.color = '#94a3b8';
    Chart.defaults.borderColor = 'rgba(42, 52, 65, 0.5)';
    Chart.defaults.font.family = 'Inter';

    // Weekly Bar Chart
    const weeklyCtx = document.getElementById('weeklyChart').getContext('2d');
    new Chart(weeklyCtx, {
        type: 'bar',
        data: {
            labels: @json(collect($chartData)->pluck('date')),
            datasets: [
                {
                    label: 'Pemasukan',
                    data: @json(collect($chartData)->pluck('income')),
                    backgroundColor: 'rgba(16, 185, 129, 0.8)',
                    hoverBackgroundColor: 'rgba(16, 185, 129, 1)',
                    borderRadius: 8,
                    borderSkipped: false,
                    barThickness: 24,
                },
                {
                    label: 'Pengeluaran',
                    data: @json(collect($chartData)->pluck('expense')),
                    backgroundColor: 'rgba(244, 63, 94, 0.8)',
                    hoverBackgroundColor: 'rgba(244, 63, 94, 1)',
                    borderRadius: 8,
                    borderSkipped: false,
                    barThickness: 24,
                }
            ]
        },
        options: {
            responsive: true,
            interaction: { mode: 'index', intersect: false },
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#1E2532',
                    titleColor: '#f8fafc',
                    bodyColor: '#94a3b8',
                    borderColor: 'rgba(42, 52, 65, 0.5)',
                    borderWidth: 1,
                    cornerRadius: 12,
                    padding: 12,
                    callbacks: {
                        label: (context) => {
                            return context.dataset.label + ': Rp ' + context.parsed.y.toLocaleString('id-ID');
                        }
                    }
                }
            },
            scales: {
                x: {
                    grid: { display: false },
                    ticks: { color: '#64748b' }
                },
                y: {
                    grid: { color: 'rgba(42, 52, 65, 0.3)', drawBorder: false },
                    ticks: {
                        color: '#64748b',
                        callback: (value) => {
                            if (value >= 1000000) return 'Rp ' + (value/1000000).toFixed(1) + 'M';
                            if (value >= 1000) return 'Rp ' + (value/1000).toFixed(0) + 'k';
                            return 'Rp ' + value;
                        }
                    }
                }
            }
        }
    });

    // Category Doughnut Chart
    const categoryCtx = document.getElementById('categoryChart').getContext('2d');
    const categoryData = @json($categoryBreakdown);
    new Chart(categoryCtx, {
        type: 'doughnut',
        data: {
            labels: categoryData.map(c => c.name),
            datasets: [{
                data: categoryData.map(c => c.total),
                backgroundColor: categoryData.map(c => c.color),
                borderWidth: 0,
                hoverOffset: 8,
            }]
        },
        options: {
            responsive: true,
            cutout: '72%',
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#1E2532',
                    titleColor: '#f8fafc',
                    bodyColor: '#94a3b8',
                    borderColor: 'rgba(42, 52, 65, 0.5)',
                    borderWidth: 1,
                    cornerRadius: 12,
                    padding: 12,
                    callbacks: {
                        label: (context) => {
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const pct = ((context.parsed / total) * 100).toFixed(1);
                            return context.label + ': Rp ' + context.parsed.toLocaleString('id-ID') + ' (' + pct + '%)';
                        }
                    }
                }
            }
        }
    });
</script>
@endpush
