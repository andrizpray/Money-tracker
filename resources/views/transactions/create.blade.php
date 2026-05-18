@extends('layouts.app')
@section('title', 'Tambah Transaksi - Money Tracker')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="card rounded-2xl p-6">
        <h1 class="text-xl font-bold mb-6">➕ Tambah Transaksi Baru</h1>

        <form action="{{ route('transactions.store') }}" method="POST" class="space-y-5">
            @csrf

            <!-- Type -->
            <div>
                <label class="block text-sm font-medium text-text2 mb-2">Tipe Transaksi</label>
                <div class="grid grid-cols-2 gap-3">
                    <label class="cursor-pointer">
                        <input type="radio" name="type" value="expense" class="peer hidden" {{ old('type', 'expense') === 'expense' ? 'checked' : '' }}>
                        <div class="p-4 rounded-xl border-2 border-surface2 peer-checked:border-danger peer-checked:bg-danger/10 text-center transition-all">
                            <span class="text-2xl">📉</span>
                            <p class="text-sm font-medium mt-1">Pengeluaran</p>
                        </div>
                    </label>
                    <label class="cursor-pointer">
                        <input type="radio" name="type" value="income" class="peer hidden" {{ old('type') === 'income' ? 'checked' : '' }}>
                        <div class="p-4 rounded-xl border-2 border-surface2 peer-checked:border-success peer-checked:bg-success/10 text-center transition-all">
                            <span class="text-2xl">📈</span>
                            <p class="text-sm font-medium mt-1">Pemasukan</p>
                        </div>
                    </label>
                </div>
            </div>

            <!-- Amount -->
            <div>
                <label class="block text-sm font-medium text-text2 mb-2">Jumlah (Rp)</label>
                <input type="number" name="amount" value="{{ old('amount') }}" required min="0.01" step="0.01" placeholder="0" class="w-full px-4 py-3 rounded-xl bg-surface2 border border-surface2 text-text placeholder-text2 focus:border-accent focus:outline-none text-lg font-semibold">
                @error('amount')<p class="text-danger text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <!-- Category -->
            <div>
                <label class="block text-sm font-medium text-text2 mb-2">Kategori</label>
                <select name="category_id" required class="w-full px-4 py-3 rounded-xl bg-surface2 border border-surface2 text-text focus:border-accent focus:outline-none">
                    <option value="">Pilih kategori...</option>
                    @foreach($categories->where('type', 'expense') as $cat)
                        <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->icon }} {{ $cat->name }}</option>
                    @endforeach
                </select>
                @error('category_id')<p class="text-danger text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <!-- Description -->
            <div>
                <label class="block text-sm font-medium text-text2 mb-2">Deskripsi</label>
                <input type="text" name="description" value="{{ old('description') }}" placeholder="Contoh: Beli makan siang" class="w-full px-4 py-3 rounded-xl bg-surface2 border border-surface2 text-text placeholder-text2 focus:border-accent focus:outline-none">
            </div>

            <!-- Date -->
            <div>
                <label class="block text-sm font-medium text-text2 mb-2">Tanggal</label>
                <input type="date" name="transaction_date" value="{{ old('transaction_date', date('Y-m-d')) }}" required class="w-full px-4 py-3 rounded-xl bg-surface2 border border-surface2 text-text focus:border-accent focus:outline-none">
            </div>

            <!-- Actions -->
            <div class="flex gap-3 pt-4">
                <a href="{{ route('transactions.index') }}" class="flex-1 px-5 py-3 rounded-xl bg-surface2 text-text2 text-center font-medium hover:text-text transition-colors">Batal</a>
                <button type="submit" class="flex-1 px-5 py-3 rounded-xl btn-primary text-white font-medium">Simpan</button>
            </div>
        </form>
    </div>
</div>
@endsection
