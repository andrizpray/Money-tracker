@extends('layouts.guest')
@section('title', 'Reset Password - Money Tracker')

@section('content')
<h2 class="text-xl font-bold mb-6 text-center">Password Baru</h2>

<form method="POST" action="{{ route('password.store') }}" class="space-y-4">
    @csrf
    <input type="hidden" name="token" value="{{ $request->route('token') }}">

    <div>
        <label class="block text-sm font-medium text-text2 mb-2">Email</label>
        <input type="email" name="email" value="{{ old('email', $request->email) }}" required class="w-full px-4 py-3 rounded-xl bg-surface2 border border-surface2 text-text placeholder-text2 focus:border-accent focus:outline-none">
        @error('email')<p class="text-danger text-xs mt-1">{{ $message }}</p>@enderror
    </div>

    <div>
        <label class="block text-sm font-medium text-text2 mb-2">Password Baru</label>
        <input type="password" name="password" required class="w-full px-4 py-3 rounded-xl bg-surface2 border border-surface2 text-text placeholder-text2 focus:border-accent focus:outline-none">
        @error('password')<p class="text-danger text-xs mt-1">{{ $message }}</p>@enderror
    </div>

    <div>
        <label class="block text-sm font-medium text-text2 mb-2">Konfirmasi Password</label>
        <input type="password" name="password_confirmation" required class="w-full px-4 py-3 rounded-xl bg-surface2 border border-surface2 text-text placeholder-text2 focus:border-accent focus:outline-none">
    </div>

    <button type="submit" class="w-full px-5 py-3 rounded-xl text-white font-medium" style="background: linear-gradient(135deg, #6366f1 0%, #818cf8 100%);">Reset Password</button>
</form>
@endsection
