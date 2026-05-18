@extends('layouts.app')
@section('title', 'Dashboard - Money Tracker')

@section('content')
<div class="space-y-6">
    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Balance -->
        <div class="card rounded-2xl p-5 glow">
            <div class="flex items-center justify-between mb-3">
                <span class="text-text2 text-sm">Saldo Bulan Ini</span>
                <div class="w-10 h-10 rounded-xl bg-accent/20 flex items-center justify-center text-xl">💵</div>
            </div>
            <p class="text-2xl font-bold text-text">Rp{{ number_format($month['balance'], 0, ',', '.') }}</p>
            <p class="text-xs text-text2 mt-1">{{ $month['count'] }} transaksi</p>
        </div>

        <!-- Income -->
        <div class="card rounded-2xl p-5">
            <div class="flex items-center justify-between mb-3">
                <span class="text-text2 text-sm">Pemasukan</span>
                <div class="w-10 h-10 rounded-xl bg-success/20 flex items-center justify-center text-xl">📈</div>
            </div>
            <p class="text-2xl font-bold text-success">Rp{{ number_format($month['income'], 0, ',', '.') }}</p>
            <p class="text-xs text-text2 mt-1">Bulan ini</p>
        </div>

        <!-- Expense -->
        <div class="card rounded-2xl p-5">
            <div class="flex items-center justify-between mb-3">
                <span class="text-text2 text-sm">Pengeluaran</span>
                <div class="w-10 h-10 rounded-xl bg-danger/20 flex items-center justify-center text-xl">📉</div>
            </div>
            <p class="text-2xl font-bold text-danger">Rp{{ number_format($month['expense'], 0, ',', '.') }}</p>
            <p class="text-xs text-text2 mt-1">Bulan ini</p>
        </div>

        <!-- Today -->
        <div class="card rounded-2xl p-5">
            <div class="flex items-center justify-between mb-3">
                <span class="text-text2 text-sm">Hari Ini</span>
                <div class="w-10 h-10 rounded-xl bg-warning/20 flex items-center justify-center text-xl">📅</div>
            </div>
            <p class="text-2xl font-bold {{ $today['balance'] >= 0 ? 'text-success' : 'text-danger' }}">
                Rp{{ number_format($today['balance'], 0, ',', '.') }}
            </p>
            <p class="text-xs text-text2 mt-1">{{ $today['count'] }} transaksi hari ini</p>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Weekly Chart -->
        <div class="lg:col-span-2 card rounded-2xl p-6">
            <h3 class="text-lg font-semibold mb-4">📊 Tren 7 Hari Terakhir</h3>
            <canvas id="weeklyChart" height="120"></canvas>
        </div>

        <!-- Category Pie -->
        <div class="card rounded-2xl p-6">
            <h3 class="text-lg font-semibold mb-4">🍩 Kategori Pengeluaran</h3>
            <canvas id="categoryChart" height="200"></canvas>
            <div class="mt-4 space-y-2">
                @foreach($categoryBreakdown->take(5) as $cat)
                <div class="flex items-center justify-between text-sm">
                    <div class="flex items-center gap-2">
                        <span>{{ $cat['icon'] }}</span>
                        <span class="text-text2">{{ $cat['name'] }}</span>
                    </div>
                    <span class="text-text font-medium">Rp{{ number_format($cat['total'], 0, ',', '.') }}</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Recent Transactions -->
    <div class="card rounded-2xl p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold">📋 Transaksi Terakhir</h3>
            <a href="{{ route('transactions.index') }}" class="text-accent text-sm hover:underline">Lihat semua →</a>
        </div>

        @if($recentTransactions->isEmpty())
            <div class="text-center py-12 text-text2">
                <p class="text-4xl mb-3">📭</p>
                <p>Belum ada transaksi</p>
                <a href="{{ route('transactions.create') }}" class="btn-primary inline-block mt-4 px-6 py-2 rounded-lg text-white text-sm font-medium">+ Tambah Transaksi</a>
            </div>
        @else
            <div class="space-y-3">
                @foreach($recentTransactions as $t)
                <div class="flex items-center justify-between p-3 rounded-xl bg-surface2/50 hover:bg-surface2 transition-colors">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl {{ $t->type === 'income' ? 'bg-success/20' : 'bg-danger/20' }} flex items-center justify-center text-lg">
                            {{ $t->category?->icon ?? '📦' }}
                        </div>
                        <div>
                            <p class="text-sm font-medium text-text">{{ $t->description }}</p>
                            <p class="text-xs text-text2">{{ $t->category?->name ?? '-' }} • {{ $t->transaction_date->format('d M Y') }}</p>
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

    <!-- Quick Add -->
    <div class="fixed bottom-6 right-6">
        <a href="{{ route('transactions.create') }}" class="w-14 h-14 rounded-full btn-primary flex items-center justify-center text-white text-2xl shadow-lg shadow-accent/30 hover:scale-110 transition-transform">
            +
        </a>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Weekly Chart
    const weeklyCtx = document.getElementById('weeklyChart').getContext('2d');
    new Chart(weeklyCtx, {
        type: 'bar',
        data: {
            labels: @json(collect($chartData)->pluck('date')),
            datasets: [
                {
                    label: 'Pemasukan',
                    data: @json(collect($chartData)->pluck('income')),
                    backgroundColor: 'rgba(34, 197, 94, 0.6)',
                    borderColor: '#22c55e',
                    borderWidth: 1,
                    borderRadius: 6,
                },
                {
                    label: 'Pengeluaran',
                    data: @json(collect($chartData)->pluck('expense')),
                    backgroundColor: 'rgba(239, 68, 68, 0.6)',
                    borderColor: '#ef4444',
                    borderWidth: 1,
                    borderRadius: 6,
                }
            ]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    labels: { color: '#94a3b8' }
                }
            },
            scales: {
                x: {
                    ticks: { color: '#94a3b8' },
                    grid: { color: 'rgba(148, 163, 184, 0.1)' }
                },
                y: {
                    ticks: {
                        color: '#94a3b8',
                        callback: v => 'Rp' + v.toLocaleString('id-ID')
                    },
                    grid: { color: 'rgba(148, 163, 184, 0.1)' }
                }
            }
        }
    });

    // Category Pie Chart
    const categoryCtx = document.getElementById('categoryChart').getContext('2d');
    new Chart(categoryCtx, {
        type: 'doughnut',
        data: {
            labels: @json($categoryBreakdown->pluck('name')),
            datasets: [{
                data: @json($categoryBreakdown->pluck('total')),
                backgroundColor: @json($categoryBreakdown->pluck('color')),
                borderWidth: 0,
                hoverOffset: 8,
            }]
        },
        options: {
            responsive: true,
            cutout: '65%',
            plugins: {
                legend: { display: false }
            }
        }
    });
</script>
@endpush
