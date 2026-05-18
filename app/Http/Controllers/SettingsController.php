<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SettingsController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        return view('settings.index', compact('user'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'monthly_budget' => 'nullable|numeric|min:0',
            'bot_token' => 'nullable|string|max:255',
        ]);

        Auth::user()->update($validated);

        return redirect()->route('settings.index')
            ->with('success', 'Pengaturan berhasil disimpan!');
    }
}
