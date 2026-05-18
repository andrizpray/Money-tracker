@extends('layouts.app')
@section('title', 'Kategori - Money Tracker')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-bold">📂 Kategori</h1>
        <p class="text-text2 text-sm">Kelola kategori pemasukan dan pengeluaran</p>
    </div>

    <!-- Add Category -->
    <div class="card rounded-2xl p-5">
        <h3 class="font-semibold mb-4">➕ Tambah Kategori Baru</h3>
        <form action="{{ route('categories.store') }}" method="POST" class="flex flex-wrap gap-3">
            @csrf
            <input type="text" name="name" placeholder="Nama kategori" required class="flex-1 min-w-[150px] px-4 py-2 rounded-xl bg-surface2 border border-surface2 text-text placeholder-text2 focus:border-accent focus:outline-none text-sm">
            <select name="type" required class="px-4 py-2 rounded-xl bg-surface2 border border-surface2 text-text focus:border-accent focus:outline-none text-sm">
                <option value="expense">📉 Pengeluaran</option>
                <option value="income">📈 Pemasukan</option>
            </select>
            <input type="text" name="icon" placeholder="Emoji (🍔)" maxlength="10" class="w-20 px-4 py-2 rounded-xl bg-surface2 border border-surface2 text-text text-center focus:border-accent focus:outline-none text-sm">
            <input type="color" name="color" value="#6366f1" class="w-12 h-10 rounded-xl cursor-pointer bg-surface2 border border-surface2">
            <button type="submit" class="px-5 py-2 rounded-xl btn-primary text-white text-sm font-medium">Tambah</button>
        </form>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Expense Categories -->
        <div class="card rounded-2xl p-5">
            <h3 class="font-semibold mb-4 text-danger">📉 Pengeluaran</h3>
            <div class="space-y-2">
                @forelse($expenseCategories as $cat)
                <div class="flex items-center justify-between p-3 rounded-xl bg-surface2/50">
                    <div class="flex items-center gap-3">
                        <span class="text-xl">{{ $cat->icon }}</span>
                        <span class="text-sm font-medium">{{ $cat->name }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="w-4 h-4 rounded-full" style="background: {{ $cat->color }}"></div>
                        @if(!$cat->is_default)
                        <form action="{{ route('categories.destroy', $cat) }}" method="POST" onsubmit="return confirm('Hapus kategori ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="p-1.5 rounded-lg hover:bg-danger/20 text-text2 hover:text-danger transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
                @empty
                <p class="text-text2 text-sm text-center py-4">Belum ada kategori</p>
                @endforelse
            </div>
        </div>

        <!-- Income Categories -->
        <div class="card rounded-2xl p-5">
            <h3 class="font-semibold mb-4 text-success">📈 Pemasukan</h3>
            <div class="space-y-2">
                @forelse($incomeCategories as $cat)
                <div class="flex items-center justify-between p-3 rounded-xl bg-surface2/50">
                    <div class="flex items-center gap-3">
                        <span class="text-xl">{{ $cat->icon }}</span>
                        <span class="text-sm font-medium">{{ $cat->name }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="w-4 h-4 rounded-full" style="background: {{ $cat->color }}"></div>
                        @if(!$cat->is_default)
                        <form action="{{ route('categories.destroy', $cat) }}" method="POST" onsubmit="return confirm('Hapus kategori ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="p-1.5 rounded-lg hover:bg-danger/20 text-text2 hover:text-danger transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
                @empty
                <p class="text-text2 text-sm text-center py-4">Belum ada kategori</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
