<?php

namespace App\Telegram\Commands;

use App\Models\User;
use App\Services\ReportService;
use Telegram\Bot\Commands\Command;

class BulanIniCommand extends Command
{
    protected string $name = 'bulanini';
    protected string $description = 'Laporan bulan ini';

    public function __construct(
        private ReportService $report
    ) {}

    public function handle(): void
    {
        $telegramUser = $this->getUpdate()->getMessage()->getFrom();
        $user = User::where('telegram_id', $telegramUser->getId())->first();

        if (!$user) {
            $this->replyWithMessage(['text' => "⚠️ Ketik /start dulu ya!"]);
            return;
        }

        $summary = $this->report->getMonthlySummary($user);

        $text = "📊 <b>Laporan Bulan Ini</b>\n";
        $text .= "📅 " . now()->format('F Y') . "\n";
        $text .= "━━━━━━━━━━━━━━━━━━━━\n\n";

        $text .= "📈 Total Pemasukan: <b>Rp" . number_format($summary['income'], 0, ',', '.') . "</b>\n";
        $text .= "📉 Total Pengeluaran: <b>Rp" . number_format($summary['expense'], 0, ',', '.') . "</b>\n";

        $balanceEmoji = $summary['balance'] >= 0 ? '🟢' : '🔴';
        $text .= "{$balanceEmoji} Saldo: <b>Rp" . number_format($summary['balance'], 0, ',', '.') . "</b>\n";
        $text .= "📝 Total transaksi: {$summary['count']}\n";

        // Budget info
        if ($user->monthly_budget) {
            $usedPercent = $summary['expense'] > 0 ? round(($summary['expense'] / $user->monthly_budget) * 100) : 0;
            $budgetEmoji = $usedPercent >= 90 ? '🔴' : ($usedPercent >= 70 ? '🟡' : '🟢');
            $text .= "\n💼 Budget: Rp" . number_format($user->monthly_budget, 0, ',', '.') . "\n";
            $text .= "{$budgetEmoji} Terpakai: {$usedPercent}%\n";
        }

        // Category breakdown
        if ($summary['by_category']->isNotEmpty()) {
            $text .= "\n━━━━━━━━━━━━━━━━━━━━\n";
            $text .= "<b>Pengeluaran per Kategori:</b>\n\n";

            foreach ($summary['by_category'] as $cat) {
                $text .= "{$cat['icon']} {$cat['name']}: Rp" . number_format($cat['total'], 0, ',', '.') . " ({$cat['count']}x)\n";
            }
        }

        $this->replyWithMessage([
            'text' => $text,
            'parse_mode' => 'HTML',
        ]);
    }
}
