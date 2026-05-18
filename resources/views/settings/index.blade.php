@extends('layouts.app')
@section('title', 'Pengaturan - Money Tracker')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div>
        <h1 class="text-2xl font-bold">⚙️ Pengaturan</h1>
        <p class="text-text2 text-sm">Konfigurasi akun dan bot Telegram</p>
    </div>

    <!-- Profile -->
    <div class="card rounded-2xl p-6">
        <h3 class="font-semibold mb-4">👤 Profil</h3>
        <form action="{{ route('settings.update') }}" method="POST" class="space-y-4">
            @csrf @method('PUT')

            <div>
                <label class="block text-sm font-medium text-text2 mb-2">Nama</label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" required class="w-full px-4 py-3 rounded-xl bg-surface2 border border-surface2 text-text focus:border-accent focus:outline-none">
            </div>

            <div>
                <label class="block text-sm font-medium text-text2 mb-2">Email</label>
                <input type="email" value="{{ $user->email }}" disabled class="w-full px-4 py-3 rounded-xl bg-surface2 border border-surface2 text-text2 cursor-not-allowed">
            </div>

            <div>
                <label class="block text-sm font-medium text-text2 mb-2">Budget Bulanan (Rp)</label>
                <input type="number" name="monthly_budget" value="{{ old('monthly_budget', $user->monthly_budget) }}" placeholder="0 = tanpa budget" min="0" class="w-full px-4 py-3 rounded-xl bg-surface2 border border-surface2 text-text focus:border-accent focus:outline-none">
            </div>

            <div>
                <label class="block text-sm font-medium text-text2 mb-2">Telegram Bot Token</label>
                <input type="text" name="bot_token" value="{{ old('bot_token', $user->bot_token) }}" placeholder="xxxxxxxxxx:xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx" class="w-full px-4 py-3 rounded-xl bg-surface2 border border-surface2 text-text focus:border-accent focus:outline-none text-sm font-mono">
                <p class="text-xs text-text2 mt-1">Dapatkan dari @BotFather di Telegram</p>
            </div>

            @if($user->telegram_id)
            <div class="p-3 rounded-xl bg-success/10 border border-success/30">
                <p class="text-success text-sm">✅ Telegram terhubung: <strong>{{ $user->telegram_username ?? $user->telegram_id }}</strong></p>
            </div>
            @else
            <div class="p-3 rounded-xl bg-warning/10 border border-warning/30">
                <p class="text-warning text-sm">⚠️ Telegram belum terhubung. Ketik /start di bot kamu untuk menghubungkan.</p>
            </div>
            @endif

            <button type="submit" class="w-full px-5 py-3 rounded-xl btn-primary text-white font-medium">Simpan Pengaturan</button>
        </form>
    </div>

    <!-- Webhook Info -->
    <div class="card rounded-2xl p-6">
        <h3 class="font-semibold mb-4">🔗 Webhook Telegram</h3>
        <div class="space-y-3">
            <div class="p-3 rounded-xl bg-surface2">
                <p class="text-xs text-text2 mb-1">Webhook URL:</p>
                <code class="text-sm text-accent break-all">{{ url('/telegram/webhook') }}</code>
            </div>
            <p class="text-xs text-text2">Set webhook via: <code class="text-accent">POST /telegram/set-webhook</code> dengan body <code class="text-accent">{"url": "{{ url('/telegram/webhook') }}"}</code></p>
        </div>
    </div>
</div>
@endsection
