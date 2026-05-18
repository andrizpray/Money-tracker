@extends('layouts.app')
@section('title', 'Laporan - Money Tracker')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold">📈 Laporan</h1>
            <p class="text-text2 text-sm">Analisis keuangan kamu</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('reports.export.pdf', request()->query()) }}" class="px-4 py-2 rounded-xl bg-danger/15 text-danger text-sm font-medium hover:bg-danger/25 transition-colors">📄 Export PDF</a>
            <a href="{{ route('reports.export.csv', request()->query()) }}" class="px-4 py-2 rounded-xl bg-success/15 text-success text-sm font-medium hover:bg-success/25 transition-colors">📊 Export CSV</a>
        </div>
    </div>

    <!-- Period Filter -->
    <div class="card rounded-2xl p-4">
        <form method="GET" class="flex flex-wrap gap-3">
            <select name="period" class="px-4 py-2 rounded-xl bg-surface2 border border-surface2 text-text focus:border-accent focus:outline-none text-sm">
                <option value="monthly" {{ $period === 'monthly' ? 'selected' : '' }}>Bulanan</option>
                <option value="weekly" {{ $period === 'weekly' ? 'selected' : '' }}>Mingguan</option>
                <option value="daily" {{ $period === 'daily' ? 'selected' : '' }}>Harian</option>
            </select>
            <select name="month" class="px-4 py-2 rounded-xl bg-surface2 border border-surface2 text-text focus:border-accent focus:outline-none text-sm">
                @for($m = 1; $m <= 12; $m++)
                    <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>{{ date('F', mktime(0, 0, 0, $m, 1)) }}</option>
                @endfor
            </select>
            <select name="year" class="px-4 py-2 rounded-xl bg-surface2 border border-surface2 text-text focus:border-accent focus:outline-none text-sm">
                @for($y = date('Y'); $y >= date('Y') - 3; $y--)
                    <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                @endfor
            </select>
            <button type="submit" class="px-5 py-2 rounded-xl bg-accent text-white text-sm font-medium">Tampilkan</button>
        </form>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="card rounded-2xl p-5">
            <p class="text-text2 text-sm mb-1">Total Pemasukan</p>
            <p class="text-2xl font-bold text-success">Rp{{ number_format($data['income'], 0, ',', '.') }}</p>
        </div>
        <div class="card rounded-2xl p-5">
            <p class="text-text2 text-sm mb-1">Total Pengeluaran</p>
            <p class="text-2xl font-bold text-danger">Rp{{ number_format($data['expense'], 0, ',', '.') }}</p>
        </div>
        <div class="card rounded-2xl p-5">
            <p class="text-text2 text-sm mb-1">Saldo</p>
            <p class="text-2xl font-bold {{ $data['balance'] >= 0 ? 'text-success' : 'text-danger' }}">Rp{{ number_format($data['balance'], 0, ',', '.') }}</p>
        </div>
    </div>

    <!-- Chart -->
    @if(isset($data['daily']) && count($data['daily']) > 0)
    <div class="card rounded-2xl p-6">
        <h3 class="text-lg font-semibold mb-4">📊 Grafik {{ ucfirst($period) }}</h3>
        <canvas id="reportChart" height="100"></canvas>
    </div>
    @endif

    <!-- Category Breakdown -->
    @if(isset($data['by_category']) && $data['by_category']->isNotEmpty())
    <div class="card rounded-2xl p-6">
        <h3 class="text-lg font-semibold mb-4">📂 Pengeluaran per Kategori</h3>
        <div class="space-y-3">
            @foreach($data['by_category'] as $cat)
            <div class="flex items-center gap-3">
                <span class="text-xl">{{ $cat['icon'] }}</span>
                <div class="flex-1">
                    <div class="flex items-center justify-between mb-1">
                        <span class="text-sm text-text">{{ $cat['name'] }}</span>
                        <span class="text-sm font-medium text-text">Rp{{ number_format($cat['total'], 0, ',', '.') }} ({{ $cat['count'] }}x)</span>
                    </div>
                    <div class="w-full h-2 rounded-full bg-surface2">
                        <div class="h-2 rounded-full" style="width: {{ $data['expense'] > 0 ? ($cat['total'] / $data['expense']) * 100 : 0 }}%; background: {{ $cat['color'] }}"></div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Transactions Table -->
    <div class="card rounded-2xl overflow-hidden">
        <div class="px-6 py-4 border-b border-surface2">
            <h3 class="font-semibold">Detail Transaksi</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-surface2">
                        <th class="text-left px-6 py-3 text-text2 text-sm font-medium">Tanggal</th>
                        <th class="text-left px-6 py-3 text-text2 text-sm font-medium">Deskripsi</th>
                        <th class="text-left px-6 py-3 text-text2 text-sm font-medium">Kategori</th>
                        <th class="text-right px-6 py-3 text-text2 text-sm font-medium">Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data['transactions'] as $t)
                    <tr class="border-b border-surface2/50 hover:bg-surface2/30">
                        <td class="px-6 py-3 text-sm text-text2">{{ $t->transaction_date->format('d M Y') }}</td>
                        <td class="px-6 py-3 text-sm text-text">{{ $t->description }}</td>
                        <td class="px-6 py-3 text-sm">{{ $t->category?->icon ?? '📦' }} {{ $t->category?->name ?? '-' }}</td>
                        <td class="px-6 py-3 text-sm font-semibold text-right {{ $t->type === 'income' ? 'text-success' : 'text-danger' }}">
                            {{ $t->type === 'income' ? '+' : '-' }}Rp{{ number_format($t->amount, 0, ',', '.') }}
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="px-6 py-8 text-center text-text2">Tidak ada transaksi</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
@if(isset($data['daily']) && count($data['daily']) > 0)
<script>
    const ctx = document.getElementById('reportChart').getContext('2d');
    const dailyData = @json($data['daily']);

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: dailyData.map(d => d.day),
            datasets: [
                {
                    label: 'Pemasukan',
                    data: dailyData.map(d => d.income),
                    backgroundColor: 'rgba(34, 197, 94, 0.6)',
                    borderColor: '#22c55e',
                    borderWidth: 1,
                    borderRadius: 4,
                },
                {
                    label: 'Pengeluaran',
                    data: dailyData.map(d => d.expense),
                    backgroundColor: 'rgba(239, 68, 68, 0.6)',
                    borderColor: '#ef4444',
                    borderWidth: 1,
                    borderRadius: 4,
                }
            ]
        },
        options: {
            responsive: true,
            plugins: { legend: { labels: { color: '#94a3b8' } } },
            scales: {
                x: { ticks: { color: '#94a3b8' }, grid: { color: 'rgba(148, 163, 184, 0.1)' } },
                y: { ticks: { color: '#94a3b8', callback: v => 'Rp' + v.toLocaleString('id-ID') }, grid: { color: 'rgba(148, 163, 184, 0.1)' } }
            }
        }
    });
</script>
@endif
@endpush
