<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        $query = $user->transactions()->with('category');

        // Filter by type
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Filter by category
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->where('transaction_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->where('transaction_date', '<=', $request->date_to);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                  ->where('amount', 'like', "%{$search}%");
            });
        }

        $transactions = $query->orderByDesc('transaction_date')
            ->orderByDesc('created_at')
            ->paginate(20);

        $categories = $user->categories;

        return view('transactions.index', compact('transactions', 'categories'));
    }

    public function create()
    {
        $categories = Auth::user()->categories;
        return view('transactions.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:income,expense',
            'amount' => 'required|numeric|min:0.01',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string|max:500',
            'transaction_date' => 'required|date',
        ]);

        Auth::user()->transactions()->create($validated);

        return redirect()->route('transactions.index')
            ->with('success', 'Transaksi berhasil ditambahkan!');
    }

    public function edit(Transaction $transaction)
    {
        $this->authorize('update', $transaction);
        $categories = Auth::user()->categories;
        return view('transactions.edit', compact('transaction', 'categories'));
    }

    public function update(Request $request, Transaction $transaction)
    {
        $this->authorize('update', $transaction);

        $validated = $request->validate([
            'type' => 'required|in:income,expense',
            'amount' => 'required|numeric|min:0.01',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string|max:500',
            'transaction_date' => 'required|date',
        ]);

        $transaction->update($validated);

        return redirect()->route('transactions.index')
            ->with('success', 'Transaksi berhasil diperbarui!');
    }

    public function destroy(Transaction $transaction)
    {
        $this->authorize('delete', $transaction);
        $transaction->delete();

        return redirect()->route('transactions.index')
            ->with('success', 'Transaksi berhasil dihapus!');
    }

    public function import(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt|max:5120',
        ]);

        $file = $request->file('csv_file');
        $handle = fopen($file->getRealPath(), 'r');
        $header = fgetcsv($handle); // skip header

        $imported = 0;

        while (($row = fgetcsv($handle)) !== false) {
            if (count($row) < 4) continue;

            [$date, $type, $amount, $desc, $categoryName] = array_pad($row, 5, null);

            $type = strtolower(trim($type)) === 'pemasukan' ? 'income' : 'expense';
            $amount = (float) str_replace([',', '.'], '', trim($amount));
            $desc = trim($desc) ?: 'Tanpa deskripsi';
            $date = trim($date);

            // Find or create category
            $category = Auth::user()->categories()->firstOrCreate(
                ['name' => $categoryName ?: 'Lainnya'],
                ['type' => $type, 'icon' => '📦', 'color' => '#64748b']
            );

            Auth::user()->transactions()->create([
                'type' => $type,
                'amount' => $amount,
                'description' => $desc,
                'category_id' => $category->id,
                'transaction_date' => $date,
            ]);
            $imported++;
        }
        fclose($handle);

        return redirect()->route('transactions.index')
            ->with('success', "Berhasil import $imported transaksi!");
    }

}
