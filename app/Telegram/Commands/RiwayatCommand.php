<?php

namespace App\Telegram\Commands;

use App\Models\User;
use Telegram\Bot\Commands\Command;

class RiwayatCommand extends Command
{
    protected string $name = 'riwayat';
    protected string $description = 'Lihat 10 transaksi terakhir';

    public function handle(): void
    {
        $telegramUser = $this->getUpdate()->getMessage()->getFrom();
        $user = User::where('telegram_id', $telegramUser->getId())->first();

        if (!$user) {
            $this->replyWithMessage(['text' => "⚠️ Ketik /start dulu ya!"]);
            return;
        }

        $transactions = $user->transactions()
            ->with('category')
            ->orderByDesc('transaction_date')
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();

        if ($transactions->isEmpty()) {
            $this->replyWithMessage(['text' => "📭 Belum ada transaksi. Ketik /lapor untuk mulai mencatat!"]);
            return;
        }

        $text = "📋 <b>10 Transaksi Terakhir</b>\n\n";

        foreach ($transactions as $t) {
            $emoji = $t->type === 'income' ? '📈' : '📉';
            $sign = $t->type === 'income' ? '+' : '-';
            $cat = $t->category ? $t->category->icon . ' ' . $t->category->name : '📦';

            $text .= "{$emoji} <b>{$sign}Rp" . number_format($t->amount, 0, ',', '.') . "</b>\n";
            $text .= "   📝 {$t->description}\n";
            $text .= "   📂 {$cat} • " . $t->transaction_date->format('d/m/Y') . "\n\n";
        }

        $this->replyWithMessage([
            'text' => $text,
            'parse_mode' => 'HTML',
        ]);
    }
}
