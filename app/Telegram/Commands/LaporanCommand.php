<?php

namespace App\Telegram\Commands;

use App\Models\User;
use App\Services\ReportService;
use Telegram\Bot\Commands\Command;

class LaporanCommand extends Command
{
    protected string $name = 'laporan';
    protected string $description = 'Laporan hari ini';

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

        $summary = $this->report->getDailySummary($user, now());

        $text = "📊 <b>Laporan Hari Ini</b>\n";
        $text .= "📅 " . now()->format('l, d F Y') . "\n";
        $text .= "━━━━━━━━━━━━━━━━━━━━\n\n";

        $text .= "📈 Pemasukan: <b>Rp" . number_format($summary['income'], 0, ',', '.') . "</b>\n";
        $text .= "📉 Pengeluaran: <b>Rp" . number_format($summary['expense'], 0, ',', '.') . "</b>\n";

        $balanceEmoji = $summary['balance'] >= 0 ? '🟢' : '🔴';
        $text .= "{$balanceEmoji} Saldo: <b>Rp" . number_format($summary['balance'], 0, ',', '.') . "</b>\n";
        $text .= "📝 Total transaksi: {$summary['count']}\n";

        if ($summary['transactions']->isNotEmpty()) {
            $text .= "\n━━━━━━━━━━━━━━━━━━━━\n";
            $text .= "<b>Detail Transaksi:</b>\n\n";

            foreach ($summary['transactions'] as $t) {
                $emoji = $t->type === 'income' ? '📈' : '📉';
                $sign = $t->type === 'income' ? '+' : '-';
                $text .= "{$emoji} {$sign}Rp" . number_format($t->amount, 0, ',', '.') . " — {$t->description}\n";
            }
        }

        $this->replyWithMessage([
            'text' => $text,
            'parse_mode' => 'HTML',
        ]);
    }
}
