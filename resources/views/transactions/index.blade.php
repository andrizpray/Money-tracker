@extends('layouts.app')
@section('title', 'Transaksi - Money Tracker')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold">💰 Transaksi</h1>
            <p class="text-text2 text-sm">Kelola semua transaksi keuangan kamu</p>
        </div>
        <a href="{{ route('transactions.create') }}" class="btn-primary px-5 py-2.5 rounded-xl text-white font-medium inline-flex items-center gap-2 w-fit">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Tambah Transaksi
        </a>
    </div>

    <!-- Filters -->
    <div class="card rounded-2xl p-4">
        <form method="GET" class="flex flex-wrap gap-3">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="🔍 Cari transaksi..." class="flex-1 min-w-[200px] px-4 py-2 rounded-xl bg-surface2 border border-surface2 text-text placeholder-text2 focus:border-accent focus:outline-none text-sm">
            <select name="type" class="px-4 py-2 rounded-xl bg-surface2 border border-surface2 text-text focus:border-accent focus:outline-none text-sm">
                <option value="">Semua Tipe</option>
                <option value="income" {{ request('type') === 'income' ? 'selected' : '' }}>📈 Pemasukan</option>
                <option value="expense" {{ request('type') === 'expense' ? 'selected' : '' }}>📉 Pengeluaran</option>
            </select>
            <select name="category_id" class="px-4 py-2 rounded-xl bg-surface2 border border-surface2 text-text focus:border-accent focus:outline-none text-sm">
                <option value="">Semua Kategori</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->icon }} {{ $cat->name }}</option>
                @endforeach
            </select>
            <input type="date" name="date_from" value="{{ request('date_from') }}" class="px-4 py-2 rounded-xl bg-surface2 border border-surface2 text-text focus:border-accent focus:outline-none text-sm">
            <input type="date" name="date_to" value="{{ request('date_to') }}" class="px-4 py-2 rounded-xl bg-surface2 border border-surface2 text-text focus:border-accent focus:outline-none text-sm">
            <button type="submit" class="px-5 py-2 rounded-xl bg-accent text-white text-sm font-medium hover:bg-accent2 transition-colors">Filter</button>
            <a href="{{ route('transactions.index') }}" class="px-5 py-2 rounded-xl bg-surface2 text-text2 text-sm hover:text-text transition-colors">Reset</a>
        </form>
    </div>

    <!-- Transactions Table -->
    <div class="card rounded-2xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-surface2">
                        <th class="text-left px-6 py-4 text-text2 text-sm font-medium">Tanggal</th>
                        <th class="text-left px-6 py-4 text-text2 text-sm font-medium">Deskripsi</th>
                        <th class="text-left px-6 py-4 text-text2 text-sm font-medium">Kategori</th>
                        <th class="text-left px-6 py-4 text-text2 text-sm font-medium">Tipe</th>
                        <th class="text-right px-6 py-4 text-text2 text-sm font-medium">Jumlah</th>
                        <th class="text-right px-6 py-4 text-text2 text-sm font-medium">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $t)
                    <tr class="border-b border-surface2/50 hover:bg-surface2/30 transition-colors">
                        <td class="px-6 py-4 text-sm text-text2">{{ $t->transaction_date->format('d M Y') }}</td>
                        <td class="px-6 py-4 text-sm text-text">{{ $t->description }}</td>
                        <td class="px-6 py-4 text-sm">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs" style="background: {{ $t->category?->color }}20; color: {{ $t->category?->color }}">
                                {{ $t->category?->icon ?? '📦' }} {{ $t->category?->name ?? '-' }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center gap-1 text-xs font-medium px-2.5 py-1 rounded-full {{ $t->type === 'income' ? 'bg-success/15 text-success' : 'bg-danger/15 text-danger' }}">
                                {{ $t->type === 'income' ? '📈 Masuk' : '📉 Keluar' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm font-semibold text-right {{ $t->type === 'income' ? 'text-success' : 'text-danger' }}">
                            {{ $t->type === 'income' ? '+' : '-' }}Rp{{ number_format($t->amount, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('transactions.edit', $t) }}" class="p-2 rounded-lg hover:bg-surface2 text-text2 hover:text-accent transition-colors" title="Edit">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </a>
                                <form action="{{ route('transactions.destroy', $t) }}" method="POST" onsubmit="return confirm('Hapus transaksi ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-2 rounded-lg hover:bg-surface2 text-text2 hover:text-danger transition-colors" title="Hapus">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-text2">
                            <p class="text-4xl mb-3">📭</p>
                            <p>Belum ada transaksi</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($transactions->hasPages())
        <div class="px-6 py-4 border-t border-surface2">
            {{ $transactions->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
