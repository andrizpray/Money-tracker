@extends('layouts.app')
@section('title', 'Edit Transaksi - Money Tracker')

@section('content')
<div class="max-w-2xl mx-auto" x-data="{ type: '{{ old('type', $transaction->type) }}' }">
    <div class="relative overflow-hidden rounded-2xl bg-surface border border-surface3/50 shadow-card">
        <div class="p-6 border-b border-surface3/30">
            <h3 class="text-lg font-bold text-text-primary">✏️ Edit Transaksi</h3>
            <p class="text-sm text-text-tertiary mt-1">Ubah detail transaksi di bawah ini</p>
        </div>

        <form action="{{ route('transactions.update', $transaction) }}" method="POST" class="p-6 space-y-6">
            @csrf @method('PUT')

            <!-- Type Toggle (Alpine.js) -->
            <div>
                <label class="block text-sm font-medium text-text-secondary mb-3">Tipe Transaksi</label>
                <div class="grid grid-cols-2 gap-3 p-1.5 bg-surface2 rounded-xl">
                    <button type="button"
                            @click="type = 'income'"
                            :class="type === 'income'
                                ? 'bg-success/20 text-success shadow-sm ring-1 ring-success/30'
                                : 'text-text-tertiary hover:text-text-secondary'"
                            class="py-3 rounded-lg text-sm font-semibold transition-all duration-200 flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12"/></svg>
                        Pemasukan
                    </button>
                    <button type="button"
                            @click="type = 'expense'"
                            :class="type === 'expense'
                                ? 'bg-danger/20 text-danger shadow-sm ring-1 ring-danger/30'
                                : 'text-text-tertiary hover:text-text-secondary'"
                            class="py-3 rounded-lg text-sm font-semibold transition-all duration-200 flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 13l-5 5m0 0l-5-5m5 5V6"/></svg>
                        Pengeluaran
                    </button>
                </div>
                <input type="hidden" name="type" x-model="type" :value="type">
            </div>

            <!-- Amount -->
            <div>
                <label class="block text-sm font-medium text-text-secondary mb-2">Jumlah (Rp)</label>
                <div class="relative">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-text-muted font-semibold text-lg">Rp</span>
                    <input type="number" name="amount" value="{{ old('amount', $transaction->amount) }}"
                           placeholder="0" min="0.01" step="0.01" required
                           class="w-full pl-12 pr-4 py-3.5 bg-surface2 border border-surface3/50 rounded-xl text-lg text-text-primary placeholder-text-muted focus:outline-none focus:border-primary-500/50 focus:ring-2 focus:ring-primary-500/20 transition-all font-semibold">
                </div>
            </div>

            <!-- Category Grid -->
            <div>
                <label class="block text-sm font-medium text-text-secondary mb-2">Kategori</label>
                <div class="grid grid-cols-4 sm:grid-cols-6 gap-2 p-4 bg-surface2 rounded-xl border border-surface3/30">
                    @foreach($categories as $category)
                    <button type="button"
                            onclick="
                                document.querySelector('[name=category_id]').value = '{{ $category->id }}';
                                document.querySelectorAll('.cat-btn').forEach(b => b.classList.remove('ring-2', 'ring-primary-500', 'bg-primary-500/10'));
                                this.classList.add('ring-2', 'ring-primary-500', 'bg-primary-500/10');
                            "
                            class="cat-btn p-3 rounded-xl border border-surface3/30 hover:border-primary-500/40 hover:bg-primary-500/5 transition-all duration-200 flex flex-col items-center gap-1.5
                                {{ old('category_id', $transaction->category_id) == $category->id ? 'ring-2 ring-primary-500 bg-primary-500/10' : '' }}"
                            title="{{ $category->name }}">
                        <span class="text-2xl">{{ $category->icon }}</span>
                        <span class="text-xs text-text-secondary text-center leading-tight">{{ $category->name }}</span>
                    </button>
                    @endforeach
                </div>
                <input type="hidden" name="category_id" value="{{ old('category_id', $transaction->category_id) }}" required>
            </div>

            <!-- Date -->
            <div>
                <label class="block text-sm font-medium text-text-secondary mb-2">Tanggal</label>
                <input type="date" name="transaction_date"
                       value="{{ old('transaction_date', $transaction->transaction_date->format('Y-m-d')) }}" required
                       class="w-full px-4 py-3 bg-surface2 border border-surface3/50 rounded-xl text-sm text-text-primary focus:outline-none focus:border-primary-500/50 focus:ring-2 focus:ring-primary-500/20 transition-all"
                       style="color-scheme: dark;">
            </div>

            <!-- Description -->
            <div>
                <label class="block text-sm font-medium text-text-secondary mb-2">
                    Deskripsi <span class="text-text-muted font-normal">(opsional)</span>
                </label>
                <textarea name="description" rows="3" placeholder="Contoh: Beli makan siang..."
                          class="w-full px-4 py-3 bg-surface2 border border-surface3/50 rounded-xl text-sm text-text-primary placeholder-text-muted focus:outline-none focus:border-primary-500/50 focus:ring-2 focus:ring-primary-500/20 transition-all resize-none">{{ old('description', $transaction->description) }}</textarea>
            </div>

            <button type="submit"
                    class="w-full py-3.5 bg-primary-600 hover:bg-primary-500 text-white rounded-xl font-semibold transition-all duration-200 shadow-button hover:shadow-button-hover active:scale-[0.98] flex items-center justify-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                Update Transaksi
            </button>
        </form>
    </div>

    <div class="mt-4 text-center">
        <a href="{{ route('transactions.index') }}" class="text-sm text-text-tertiary hover:text-text-secondary transition-colors flex items-center justify-center gap-1">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Kembali ke daftar transaksi
        </a>
    </div>
</div>
@endsection
