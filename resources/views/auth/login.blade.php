@extends('layouts.guest')
@section('title', 'Login - Money Tracker')

@section('content')
<h2 class="text-xl font-bold mb-6 text-center">Masuk</h2>

<form method="POST" action="{{ route('login') }}" class="space-y-4">
    @csrf

    <div>
        <label class="block text-sm font-medium text-text2 mb-2">Email</label>
        <input type="email" name="email" value="{{ old('email') }}" required autofocus class="w-full px-4 py-3 rounded-xl bg-surface2 border border-surface2 text-text placeholder-text2 focus:border-accent focus:outline-none">
        @error('email')<p class="text-danger text-xs mt-1">{{ $message }}</p>@enderror
    </div>

    <div>
        <label class="block text-sm font-medium text-text2 mb-2">Password</label>
        <input type="password" name="password" required class="w-full px-4 py-3 rounded-xl bg-surface2 border border-surface2 text-text placeholder-text2 focus:border-accent focus:outline-none">
        @error('password')<p class="text-danger text-xs mt-1">{{ $message }}</p>@enderror
    </div>

    <div class="flex items-center justify-between">
        <label class="flex items-center gap-2">
            <input type="checkbox" name="remember" class="rounded bg-surface2 border-surface2 text-accent focus:ring-accent">
            <span class="text-sm text-text2">Ingat saya</span>
        </label>
        <a href="{{ route('password.request') }}" class="text-sm text-accent hover:underline">Lupa password?</a>
    </div>

    <button type="submit" class="w-full px-5 py-3 rounded-xl btn-primary text-white font-medium" style="background: linear-gradient(135deg, #6366f1 0%, #818cf8 100%);">Masuk</button>
</form>

<p class="text-center text-text2 text-sm mt-6">
    Belum punya akun? <a href="{{ route('register') }}" class="text-accent hover:underline">Daftar</a>
</p>
@endsection
