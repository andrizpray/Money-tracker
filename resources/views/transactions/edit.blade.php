@extends('layouts.app')
@section('title', 'Edit Transaksi - Money Tracker')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="card rounded-2xl p-6">
        <h1 class="text-xl font-bold mb-6">✏️ Edit Transaksi</h1>

        <form action="{{ route('transactions.update', $transaction) }}" method="POST" class="space-y-5">
            @csrf @method('PUT')

            <div>
                <label class="block text-sm font-medium text-text2 mb-2">Tipe Transaksi</label>
                <div class="grid grid-cols-2 gap-3">
                    <label class="cursor-pointer">
                        <input type="radio" name="type" value="expense" class="peer hidden" {{ old('type', $transaction->type) === 'expense' ? 'checked' : '' }}>
                        <div class="p-4 rounded-xl border-2 border-surface2 peer-checked:border-danger peer-checked:bg-danger/10 text-center transition-all">
                            <span class="text-2xl">📉</span>
                            <p class="text-sm font-medium mt-1">Pengeluaran</p>
                        </div>
                    </label>
                    <label class="cursor-pointer">
                        <input type="radio" name="type" value="income" class="peer hidden" {{ old('type', $transaction->type) === 'income' ? 'checked' : '' }}>
                        <div class="p-4 rounded-xl border-2 border-surface2 peer-checked:border-success peer-checked:bg-success/10 text-center transition-all">
                            <span class="text-2xl">📈</span>
                            <p class="text-sm font-medium mt-1">Pemasukan</p>
                        </div>
                    </label>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-text2 mb-2">Jumlah (Rp)</label>
                <input type="number" name="amount" value="{{ old('amount', $transaction->amount) }}" required min="0.01" step="0.01" class="w-full px-4 py-3 rounded-xl bg-surface2 border border-surface2 text-text placeholder-text2 focus:border-accent focus:outline-none text-lg font-semibold">
            </div>

            <div>
                <label class="block text-sm font-medium text-text2 mb-2">Kategori</label>
                <select name="category_id" required class="w-full px-4 py-3 rounded-xl bg-surface2 border border-surface2 text-text focus:border-accent focus:outline-none">
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ old('category_id', $transaction->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->icon }} {{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-text2 mb-2">Deskripsi</label>
                <input type="text" name="description" value="{{ old('description', $transaction->description) }}" class="w-full px-4 py-3 rounded-xl bg-surface2 border border-surface2 text-text placeholder-text2 focus:border-accent focus:outline-none">
            </div>

            <div>
                <label class="block text-sm font-medium text-text2 mb-2">Tanggal</label>
                <input type="date" name="transaction_date" value="{{ old('transaction_date', $transaction->transaction_date->format('Y-m-d')) }}" required class="w-full px-4 py-3 rounded-xl bg-surface2 border border-surface2 text-text focus:border-accent focus:outline-none">
            </div>

            <div class="flex gap-3 pt-4">
                <a href="{{ route('transactions.index') }}" class="flex-1 px-5 py-3 rounded-xl bg-surface2 text-text2 text-center font-medium hover:text-text transition-colors">Batal</a>
                <button type="submit" class="flex-1 px-5 py-3 rounded-xl btn-primary text-white font-medium">Update</button>
            </div>
        </form>
    </div>
</div>
@endsection
