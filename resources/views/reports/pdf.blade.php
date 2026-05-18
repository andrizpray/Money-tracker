<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Keuangan</title>
    <style>
        body { font-family: 'DejaVu Sans', Arial, sans-serif; color: #1a1a1a; margin: 0; padding: 20px; }
        h1 { color: #6366f1; font-size: 24px; margin-bottom: 5px; }
        .subtitle { color: #666; font-size: 14px; margin-bottom: 20px; }
        .summary { display: flex; gap: 20px; margin-bottom: 30px; }
        .summary-card { flex: 1; padding: 15px; border: 1px solid #e5e7eb; border-radius: 8px; }
        .summary-card h3 { margin: 0 0 5px; font-size: 12px; color: #666; }
        .summary-card p { margin: 0; font-size: 20px; font-weight: bold; }
        .income { color: #22c55e; }
        .expense { color: #ef4444; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th { background: #f3f4f6; padding: 10px; text-align: left; font-size: 12px; border-bottom: 2px solid #e5e7eb; }
        td { padding: 8px 10px; font-size: 12px; border-bottom: 1px solid #f3f4f6; }
        .text-right { text-align: right; }
    </style>
</head>
<body>
    <h1>💰 Laporan Keuangan</h1>
    <p class="subtitle">{{ $data['month'] }} — Generated {{ now()->format('d/m/Y H:i') }}</p>

    <div class="summary">
        <div class="summary-card">
            <h3>Total Pemasukan</h3>
            <p class="income">Rp{{ number_format($data['income'], 0, ',', '.') }}</p>
        </div>
        <div class="summary-card">
            <h3>Total Pengeluaran</h3>
            <p class="expense">Rp{{ number_format($data['expense'], 0, ',', '.') }}</p>
        </div>
        <div class="summary-card">
            <h3>Saldo</h3>
            <p class="{{ $data['balance'] >= 0 ? 'income' : 'expense' }}">Rp{{ number_format($data['balance'], 0, ',', '.') }}</p>
        </div>
    </div>

    <h2 style="font-size: 16px; margin-top: 30px;">Detail Transaksi</h2>
    <table>
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Deskripsi</th>
                <th>Kategori</th>
                <th>Tipe</th>
                <th class="text-right">Jumlah</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data['transactions'] as $t)
            <tr>
                <td>{{ $t->transaction_date->format('d/m/Y') }}</td>
                <td>{{ $t->description }}</td>
                <td>{{ $t->category?->name ?? '-' }}</td>
                <td>{{ $t->type === 'income' ? 'Masuk' : 'Keluar' }}</td>
                <td class="text-right {{ $t->type === 'income' ? 'income' : 'expense' }}">
                    {{ $t->type === 'income' ? '+' : '-' }}Rp{{ number_format($t->amount, 0, ',', '.') }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
