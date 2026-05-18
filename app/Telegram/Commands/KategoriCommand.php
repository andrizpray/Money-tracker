<?php

namespace App\Telegram\Commands;

use App\Models\User;
use Telegram\Bot\Commands\Command;

class KategoriCommand extends Command
{
    protected string $name = 'kategori';
    protected string $description = 'Lihat daftar kategori';

    public function handle(): void
    {
        $telegramUser = $this->getUpdate()->getMessage()->getFrom();
        $user = User::where('telegram_id', $telegramUser->getId())->first();

        if (!$user) {
            $this->replyWithMessage(['text' => "⚠️ Ketik /start dulu ya!"]);
            return;
        }

        $expenseCategories = $user->categories()->where('type', 'expense')->get();
        $incomeCategories = $user->categories()->where('type', 'income')->get();

        $text = "📂 <b>Daftar Kategori</b>\n\n";

        $text .= "📉 <b>Pengeluaran:</b>\n";
        foreach ($expenseCategories as $cat) {
            $text .= "  {$cat->icon} {$cat->name}\n";
        }

        $text .= "\n📈 <b>Pemasukan:</b>\n";
        foreach ($incomeCategories as $cat) {
            $text .= "  {$cat->icon} {$cat->name}\n";
        }

        $this->replyWithMessage([
            'text' => $text,
            'parse_mode' => 'HTML',
        ]);
    }
}
