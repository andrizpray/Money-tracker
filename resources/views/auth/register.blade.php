@extends('layouts.guest')
@section('title', 'Daftar - Money Tracker')

@section('content')
<h2 class="text-xl font-bold mb-6 text-center">Buat Akun</h2>

<form method="POST" action="{{ route('register') }}" class="space-y-4">
    @csrf

    <div>
        <label class="block text-sm font-medium text-text2 mb-2">Nama</label>
        <input type="text" name="name" value="{{ old('name') }}" required class="w-full px-4 py-3 rounded-xl bg-surface2 border border-surface2 text-text placeholder-text2 focus:border-accent focus:outline-none">
        @error('name')<p class="text-danger text-xs mt-1">{{ $message }}</p>@enderror
    </div>

    <div>
        <label class="block text-sm font-medium text-text2 mb-2">Email</label>
        <input type="email" name="email" value="{{ old('email') }}" required class="w-full px-4 py-3 rounded-xl bg-surface2 border border-surface2 text-text placeholder-text2 focus:border-accent focus:outline-none">
        @error('email')<p class="text-danger text-xs mt-1">{{ $message }}</p>@enderror
    </div>

    <div>
        <label class="block text-sm font-medium text-text2 mb-2">Password</label>
        <input type="password" name="password" required class="w-full px-4 py-3 rounded-xl bg-surface2 border border-surface2 text-text placeholder-text2 focus:border-accent focus:outline-none">
        @error('password')<p class="text-danger text-xs mt-1">{{ $message }}</p>@enderror
    </div>

    <div>
        <label class="block text-sm font-medium text-text2 mb-2">Konfirmasi Password</label>
        <input type="password" name="password_confirmation" required class="w-full px-4 py-3 rounded-xl bg-surface2 border border-surface2 text-text placeholder-text2 focus:border-accent focus:outline-none">
    </div>

    <button type="submit" class="w-full px-5 py-3 rounded-xl btn-primary text-white font-medium" style="background: linear-gradient(135deg, #6366f1 0%, #818cf8 100%);">Daftar</button>
</form>

<p class="text-center text-text2 text-sm mt-6">
    Sudah punya akun? <a href="{{ route('login') }}" class="text-accent hover:underline">Masuk</a>
</p>
@endsection
