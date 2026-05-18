<div x-data="{ showImportModal: false }">
@extends('layouts.app')
@section('title', 'Transaksi - Money Tracker')

@section('content')
<div class="space-y-6 animate-fade-in">

    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-text-primary">💰 Transaksi</h1>
            <p class="text-sm text-text-tertiary mt-1">Kelola semua transaksi keuangan kamu</p>
        </div>
        <div class="flex gap-3">
            <button @click="showImportModal = true"
                    class="px-4 py-2.5 rounded-xl bg-success/10 text-success font-medium inline-flex items-center gap-2 hover:bg-success/20 transition-colors border border-success/20 text-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                Import CSV
            </button>
            <a href="{{ route('transactions.create') }}"
               class="btn-primary px-5 py-2.5 rounded-xl text-white font-medium inline-flex items-center gap-2 text-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Tambah
            </a>
        </div>
    </div>

    <!-- Stats Bar -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
        <div class="rounded-xl bg-surface border border-surface3/30 p-4">
            <p class="text-xs text-text-tertiary mb-1">Total Transaksi</p>
            <p class="text-lg font-bold text-text-primary">{{ $transactions->total() }}</p>
        </div>
        <div class="rounded-xl bg-surface border border-surface3/30 p-4">
            <p class="text-xs text-text-tertiary mb-1">Pemasukan</p>
            <p class="text-lg font-bold text-success">Rp{{ number_format($transactions->where('type', 'income')->sum('amount'), 0, ',', '.') }}</p>
        </div>
        <div class="rounded-xl bg-surface border border-surface3/30 p-4">
            <p class="text-xs text-text-tertiary mb-1">Pengeluaran</p>
            <p class="text-lg font-bold text-danger">Rp{{ number_format($transactions->where('type', 'expense')->sum('amount'), 0, ',', '.') }}</p>
        </div>
        <div class="rounded-xl bg-surface border border-surface3/30 p-4">
            <p class="text-xs text-text-tertiary mb-1">Net Saldo</p>
            <p class="text-lg font-bold {{ ($transactions->where('type', 'income')->sum('amount') - $transactions->where('type', 'expense')->sum('amount')) >= 0 ? 'text-success' : 'text-danger' }}">
                Rp{{ number_format($transactions->where('type', 'income')->sum('amount') - $transactions->where('type', 'expense')->sum('amount'), 0, ',', '.') }}
            </p>
        </div>
    </div>

    <!-- Filters -->
    <div class="rounded-2xl bg-surface border border-surface3/50 p-5 shadow-card">
        <form method="GET" class="flex flex-wrap items-end gap-3">
            <div class="flex-1 min-w-[200px]">
                <label class="block text-xs font-medium text-text-secondary mb-2">Cari</label>
                <div class="relative">
                    <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-text-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari deskripsi..."
                           class="w-full pl-10 pr-4 py-2.5 bg-surface2 border border-surface3/50 rounded-xl text-sm text-text-primary placeholder-text-muted focus:outline-none focus:border-primary-500/50 focus:ring-2 focus:ring-primary-500/20 transition-all">
                </div>
            </div>
            <div class="w-32">
                <label class="block text-xs font-medium text-text-secondary mb-2">Tipe</label>
                <select name="type" class="w-full px-3 py-2.5 bg-surface2 border border-surface3/50 rounded-xl text-sm text-text-primary appearance-none cursor-pointer focus:outline-none focus:border-primary-500/50 focus:ring-2 focus:ring-primary-500/20 transition-all">
                    <option value="">Semua</option>
                    <option value="income" {{ request('type') === 'income' ? 'selected' : '' }}>📈 Pemasukan</option>
                    <option value="expense" {{ request('type') === 'expense' ? 'selected' : '' }}>📉 Pengeluaran</option>
                </select>
            </div>
            <div class="w-40">
                <label class="block text-xs font-medium text-text-secondary mb-2">Kategori</label>
                <select name="category_id" class="w-full px-3 py-2.5 bg-surface2 border border-surface3/50 rounded-xl text-sm text-text-primary appearance-none cursor-pointer focus:outline-none focus:border-primary-500/50 focus:ring-2 focus:ring-primary-500/20 transition-all">
                    <option value="">Semua</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->icon }} {{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="w-36">
                <label class="block text-xs font-medium text-text-secondary mb-2">Dari</label>
                <input type="date" name="date_from" value="{{ request('date_from') }}"
                       class="w-full px-3 py-2.5 bg-surface2 border border-surface3/50 rounded-xl text-sm text-text-primary focus:outline-none focus:border-primary-500/50 focus:ring-2 focus:ring-primary-500/20 transition-all">
            </div>
            <div class="w-36">
                <label class="block text-xs font-medium text-text-secondary mb-2">Sampai</label>
                <input type="date" name="date_to" value="{{ request('date_to') }}"
                       class="w-full px-3 py-2.5 bg-surface2 border border-surface3/50 rounded-xl text-sm text-text-primary focus:outline-none focus:border-primary-500/50 focus:ring-2 focus:ring-primary-500/20 transition-all">
            </div>
            <button type="submit"
                    class="px-5 py-2.5 bg-primary-600 hover:bg-primary-500 text-white rounded-xl text-sm font-medium transition-all duration-200 shadow-button hover:shadow-button-hover active:scale-95">
                Filter
            </button>
            @if(request()->anyFilled(['search', 'type', 'category_id', 'date_from', 'date_to']))
                <a href="{{ route('transactions.index') }}"
                   class="px-4 py-2.5 text-sm text-text-tertiary hover:text-text-secondary transition-colors flex items-center gap-1">
                    Reset
                </a>
            @endif
        </form>
    </div>

    <!-- Transactions Table -->
    <div class="rounded-2xl bg-surface border border-surface3/50 shadow-card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-surface3/30">
                        <th class="text-left px-6 py-4 text-xs font-semibold text-text-tertiary uppercase tracking-wider">Transaksi</th>
                        <th class="text-left px-6 py-4 text-xs font-semibold text-text-tertiary uppercase tracking-wider">Kategori</th>
                        <th class="text-left px-6 py-4 text-xs font-semibold text-text-tertiary uppercase tracking-wider">Tanggal</th>
                        <th class="text-right px-6 py-4 text-xs font-semibold text-text-tertiary uppercase tracking-wider">Jumlah</th>
                        <th class="text-center px-6 py-4 text-xs font-semibold text-text-tertiary uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $t)
                    <tr class="hover:bg-surface2/20 transition-colors duration-150 group border-b border-surface3/10 last:border-0">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-11 h-11 rounded-xl flex items-center justify-center text-xl transition-transform group-hover:scale-105
                                    {{ $t->type === 'income' ? 'bg-success/10 ring-1 ring-success/20' : 'bg-danger/10 ring-1 ring-danger/20' }}">
                                    {{ $t->category?->icon ?? '📦' }}
                                </div>
                                <div>
                                    <p class="font-medium text-text-primary text-sm">{{ $t->description ?: 'Tanpa deskripsi' }}</p>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-lg text-xs mt-1
                                        {{ $t->type === 'income' ? 'bg-success/10 text-success' : 'bg-danger/10 text-danger' }} font-medium">
                                        {{ $t->type === 'income' ? 'Pemasukan' : 'Pengeluaran' }}
                                    </span>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-3 py-1 rounded-lg text-xs bg-surface2 text-text-secondary border border-surface3/30 font-medium">
                                {{ $t->category?->name ?? '-' }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-text-secondary">{{ $t->transaction_date->format('d M Y') }}</div>
                            <div class="text-xs text-text-muted mt-0.5">{{ $t->transaction_date->format('H:i') }}</div>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <p class="font-bold text-sm {{ $t->type === 'income' ? 'text-success' : 'text-danger' }}">
                                {{ $t->type === 'income' ? '+' : '-' }}Rp{{ number_format($t->amount, 0, ',', '.') }}
                            </p>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                                <a href="{{ route('transactions.edit', $t) }}"
                                   class="p-2 rounded-lg hover:bg-primary-500/10 text-text-tertiary hover:text-primary-400 transition-colors"
                                   title="Edit">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </a>
                                <form action="{{ route('transactions.destroy', $t) }}" method="POST">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                            class="p-2 rounded-lg hover:bg-danger/10 text-text-tertiary hover:text-danger transition-colors"
                                            onclick="return confirm('Yakin ingin menghapus?')"
                                            title="Hapus">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-16 text-center">
                            <div class="w-16 h-16 rounded-2xl bg-surface2 mx-auto mb-4 flex items-center justify-center text-3xl">📭</div>
                            <p class="text-text-secondary mb-1">Belum ada transaksi</p>
                            <p class="text-xs text-text-muted">Tambahkan transaksi pertamamu atau import dari CSV</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($transactions->hasPages())
        <div class="px-6 py-4 border-t border-surface3/30 flex flex-col sm:flex-row items-center justify-between gap-4">
            <p class="text-xs text-text-muted">
                Menampilkan {{ $transactions->firstItem() }} - {{ $transactions->lastItem() }} dari {{ $transactions->total() }} transaksi
            </p>
            <div class="flex items-center gap-1">
                @if($transactions->onFirstPage())
                    <span class="px-3 py-1.5 rounded-lg text-sm text-text-muted cursor-not-allowed">←</span>
                @else
                    <a href="{{ $transactions->previousPageUrl() }}" class="px-3 py-1.5 rounded-lg text-sm text-text-secondary hover:bg-surface2 transition-colors">←</a>
                @endif
                @foreach($transactions->getUrlRange(max(1, $transactions->currentPage() - 2), min($transactions->lastPage(), $transactions->currentPage() + 2)) as $page => $url)
                    <a href="{{ $url }}"
                       class="px-3 py-1.5 rounded-lg text-sm {{ $page == $transactions->currentPage() ? 'bg-primary-600 text-white' : 'text-text-secondary hover:bg-surface2' }} transition-colors">
                        {{ $page }}
                    </a>
                @endforeach
                @if($transactions->hasMorePages())
                    <a href="{{ $transactions->nextPageUrl() }}" class="px-3 py-1.5 rounded-lg text-sm text-text-secondary hover:bg-surface2 transition-colors">→</a>
                @endif
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Import Modal -->
<div x-show="showImportModal" x-cloak
     class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm"
     @click.self="showImportModal = false" @keydown.escape.window="showImportModal = false">
    <div class="relative bg-surface border border-surface3/50 rounded-2xl p-6 w-full max-w-md mx-4 shadow-card-hover"
         @click.stop>
        <div class="flex items-center justify-between mb-5">
            <div>
                <h3 class="text-lg font-bold text-text-primary">📥 Import Transaksi CSV</h3>
                <p class="text-xs text-text-muted mt-1">Upload file CSV untuk bulk import</p>
            </div>
            <button @click="showImportModal = false" class="p-2 rounded-lg hover:bg-surface2 text-text-tertiary transition-colors">✕</button>
        </div>
        <p class="text-xs text-text-secondary mb-3">Format CSV:</p>
        <div class="bg-surface2 rounded-lg p-3 mb-4 text-xs font-mono text-text-muted space-y-0.5">
            <div>Tanggal,Tipe,Jumlah,Deskripsi,Kategori</div>
            <div>2026-05-15,Pemasukan,1000000,gaji,Gaji</div>
            <div>2026-05-16,Pengeluaran,50000,Grab,Transport</div>
        </div>
        <form action="{{ route('transactions.import') }}" method="POST" enctype="multipart/form-data" @submit="showImportModal = false">
            @csrf
            <div class="border-2 border-dashed border-surface3/50 rounded-xl p-6 text-center mb-4 hover:border-primary-500/30 transition-colors cursor-pointer"
                 onclick="document.getElementById('csvInput').click()">
                <input type="file" name="csv_file" accept=".csv,.txt" required class="hidden" id="csvInput"
                       @change="if($event.target.files[0]) { $event.target.closest('form').querySelector('.file-name').textContent = $event.target.files[0].name }">
                <p class="text-3xl mb-2">📄</p>
                <p class="text-sm text-text-secondary">Klik untuk pilih file</p>
                <p class="file-name text-xs text-primary-400 mt-1"></p>
            </div>
            <div class="flex gap-3">
                <button type="button" @click="showImportModal = false"
                        class="flex-1 px-4 py-2.5 rounded-xl bg-surface2 text-text-secondary text-sm font-medium hover:bg-surface3 transition-colors">
                    Batal
                </button>
                <button type="submit"
                        class="flex-1 px-4 py-2.5 rounded-xl bg-success text-white text-sm font-semibold hover:bg-success/80 transition-colors flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                    Import
                </button>
            </div>
        </form>
    </div>
</div>

@endsection
</div>
