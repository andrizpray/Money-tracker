<?php

namespace App\Http\Controllers;

use App\Services\ReportService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function __construct(
        private ReportService $report
    ) {}

    public function index(Request $request)
    {
        $user = Auth::user();
        $period = $request->get('period', 'monthly');
        $month = $request->get('month', now()->month);
        $year = $request->get('year', now()->year);

        $data = match ($period) {
            'daily' => $this->report->getDailySummary($user, now()),
            'weekly' => $this->report->getWeeklySummary($user),
            'monthly' => $this->report->getMonthlySummary($user, $month, $year),
            default => $this->report->getMonthlySummary($user, $month, $year),
        };

        return view('reports.index', compact('data', 'period', 'month', 'year'));
    }

    public function exportPdf(Request $request)
    {
        $user = Auth::user();
        $period = $request->get('period', 'monthly');
        $month = $request->get('month', now()->month);
        $year = $request->get('year', now()->year);

        $data = $this->report->getMonthlySummary($user, $month, $year);

        $pdf = Pdf::loadView('reports.pdf', compact('data', 'user'))
            ->setPaper('a4', 'portrait');

        return $pdf->download('laporan-' . now()->format('Y-m-d') . '.pdf');
    }

    public function exportCsv(Request $request)
    {
        $user = Auth::user();
        $month = $request->get('month', now()->month);
        $year = $request->get('year', now()->year);

        $transactions = $user->transactions()
            ->with('category')
            ->whereMonth('transaction_date', $month)
            ->whereYear('transaction_date', $year)
            ->orderBy('transaction_date')
            ->get();

        $filename = 'transaksi-' . $year . '-' . str_pad($month, 2, '0', STR_PAD_LEFT) . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function () use ($transactions) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Tanggal', 'Tipe', 'Jumlah', 'Deskripsi', 'Kategori']);

            foreach ($transactions as $t) {
                fputcsv($file, [
                    $t->transaction_date->format('Y-m-d'),
                    $t->type === 'income' ? 'Pemasukan' : 'Pengeluaran',
                    $t->amount,
                    $t->description,
                    $t->category?->name ?? '-',
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
