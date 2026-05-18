<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Category;
use App\Services\ReportService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function __construct(
        private ReportService $report
    ) {}

    public function index()
    {
        $user = Auth::user();

        // Today summary
        $today = $this->report->getDailySummary($user, now());

        // This week summary
        $week = $this->report->getWeeklySummary($user);

        // This month summary
        $month = $this->report->getMonthlySummary($user);

        // Recent transactions
        $recentTransactions = $user->transactions()
            ->with('category')
            ->orderByDesc('transaction_date')
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();

        // Chart data: last 7 days
        $chartData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $summary = $this->report->getDailySummary($user, $date);
            $chartData[] = [
                'date' => $date->format('D'),
                'income' => $summary['income'],
                'expense' => $summary['expense'],
            ];
        }

        // Category breakdown for pie chart
        $categoryBreakdown = $month['by_category'];

        return view('dashboard.index', compact(
            'today', 'week', 'month', 'recentTransactions',
            'chartData', 'categoryBreakdown'
        ));
    }
}
