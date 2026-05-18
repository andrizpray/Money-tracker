<?php

namespace App\Services;

use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class ReportService
{
    public function getDailySummary(User $user, Carbon $date): array
    {
        $transactions = $user->transactions()
            ->whereDate('transaction_date', $date)
            ->with('category')
            ->get();

        return [
            'date' => $date->format('d/m/Y'),
            'income' => $transactions->where('type', 'income')->sum('amount'),
            'expense' => $transactions->where('type', 'expense')->sum('amount'),
            'balance' => $transactions->where('type', 'income')->sum('amount') - $transactions->where('type', 'expense')->sum('amount'),
            'count' => $transactions->count(),
            'transactions' => $transactions,
        ];
    }

    public function getWeeklySummary(User $user): array
    {
        $start = now()->startOfWeek();
        $end = now()->endOfWeek();

        $transactions = $user->transactions()
            ->whereBetween('transaction_date', [$start, $end])
            ->with('category')
            ->get();

        $daily = [];
        for ($d = $start->copy(); $d->lte($end); $d->addDay()) {
            $dayTransactions = $transactions->filter(fn($t) => $t->transaction_date->format('Y-m-d') === $d->format('Y-m-d'));
            $daily[] = [
                'day' => $d->format('D'),
                'date' => $d->format('d/m'),
                'income' => $dayTransactions->where('type', 'income')->sum('amount'),
                'expense' => $dayTransactions->where('type', 'expense')->sum('amount'),
            ];
        }

        return [
            'income' => $transactions->where('type', 'income')->sum('amount'),
            'expense' => $transactions->where('type', 'expense')->sum('amount'),
            'balance' => $transactions->where('type', 'income')->sum('amount') - $transactions->where('type', 'expense')->sum('amount'),
            'count' => $transactions->count(),
            'daily' => $daily,
            'transactions' => $transactions,
        ];
    }

    public function getMonthlySummary(User $user, ?int $month = null, ?int $year = null): array
    {
        $month = $month ?? now()->month;
        $year = $year ?? now()->year;

        $transactions = $user->transactions()
            ->whereMonth('transaction_date', $month)
            ->whereYear('transaction_date', $year)
            ->with('category')
            ->get();

        // Category breakdown
        $byCategory = $transactions->where('type', 'expense')
            ->groupBy('category_id')
            ->map(function ($items) {
                return [
                    'name' => $items->first()->category?->name ?? 'Unknown',
                    'icon' => $items->first()->category?->icon ?? '📦',
                    'color' => $items->first()->category?->color ?? '#64748b',
                    'total' => $items->sum('amount'),
                    'count' => $items->count(),
                ];
            })
            ->sortByDesc('total')
            ->values();

        // Daily breakdown for chart
        $daysInMonth = Carbon::create($year, $month)->daysInMonth;
        $daily = [];
        for ($d = 1; $d <= $daysInMonth; $d++) {
            $date = Carbon::create($year, $month, $d);
            $dayTransactions = $transactions->filter(fn($t) => $t->transaction_date->format('Y-m-d') === $date->format('Y-m-d'));
            $daily[] = [
                'day' => $d,
                'income' => $dayTransactions->where('type', 'income')->sum('amount'),
                'expense' => $dayTransactions->where('type', 'expense')->sum('amount'),
            ];
        }

        return [
            'month' => Carbon::create($year, $month)->format('F Y'),
            'income' => $transactions->where('type', 'income')->sum('amount'),
            'expense' => $transactions->where('type', 'expense')->sum('amount'),
            'balance' => $transactions->where('type', 'income')->sum('amount') - $transactions->where('type', 'expense')->sum('amount'),
            'count' => $transactions->count(),
            'by_category' => $byCategory,
            'daily' => $daily,
            'transactions' => $transactions,
        ];
    }

    public function getCategoryBreakdown(User $user, string $type, Carbon $start, Carbon $end): Collection
    {
        return $user->transactions()
            ->where('type', $type)
            ->whereBetween('transaction_date', [$start, $end])
            ->with('category')
            ->get()
            ->groupBy('category_id')
            ->map(function ($items) {
                return [
                    'name' => $items->first()->category?->name ?? 'Unknown',
                    'icon' => $items->first()->category?->icon ?? '📦',
                    'color' => $items->first()->category?->color ?? '#64748b',
                    'total' => $items->sum('amount'),
                    'count' => $items->count(),
                ];
            })
            ->sortByDesc('total')
            ->values();
    }
}
