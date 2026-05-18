@extends('layouts.guest')
@section('title', 'Reset Password - Money Tracker')

@section('content')
<h2 class="text-xl font-bold mb-2 text-center">Reset Password</h2>
<p class="text-text2 text-sm text-center mb-6">Masukkan email untuk link reset</p>

<form method="POST" action="{{ route('password.email') }}" class="space-y-4">
    @csrf
    <div>
        <label class="block text-sm font-medium text-text2 mb-2">Email</label>
        <input type="email" name="email" value="{{ old('email') }}" required class="w-full px-4 py-3 rounded-xl bg-surface2 border border-surface2 text-text placeholder-text2 focus:border-accent focus:outline-none">
        @error('email')<p class="text-danger text-xs mt-1">{{ $message }}</p>@enderror
    </div>
    <button type="submit" class="w-full px-5 py-3 rounded-xl text-white font-medium" style="background: linear-gradient(135deg, #6366f1 0%, #818cf8 100%);">Kirim Link Reset</button>
</form>

<p class="text-center text-text2 text-sm mt-6">
    <a href="{{ route('login') }}" class="text-accent hover:underline">Kembali ke Login</a>
</p>
@endsection
